<?php
// app/controllers/AttendanceController.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

/* =========================
   CHECK-IN
========================= */
function attendance_checkin(PDO $pdo)
{
    require_login('user');

    $token = $_GET['token'] ?? '';

    if ($token === '') {
        $status = 'error';
        $title = 'Invalid Token';
        $message = 'Invalid check-in token.';
        require_once __DIR__ . '/../views/user/scan_result.php';
        return;
    }

    $stmt = $pdo->prepare(
        "SELECT * FROM drive_qr_tokens
         WHERE token = ?
           AND type = 'checkin'
           AND expires_at > NOW()
         LIMIT 1"
    );
    $stmt->execute([$token]);
    $qr = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$qr) {
        $status = 'error';
        $title = 'Token Expired';
        $message = 'This check-in QR is invalid or expired.';
        require_once __DIR__ . '/../views/user/scan_result.php';
        return;
    }

    $user_id = (int) $_SESSION['user_id'];
    $project_id = (int) $qr['project_id'];

    // CRITICAL: Check if user has signed up for this event
    $stmt = $pdo->prepare("SELECT id FROM volunteer_applications WHERE user_id = ? AND project_id = ?");
    $stmt->execute([$user_id, $project_id]);
    $application = $stmt->fetch();

    if (!$application) {
        $status = 'error';
        $title = '🚫 Access Denied';
        $message = "You haven't signed up for this event. Registration is required to participate and earn points.";
        require_once __DIR__ . '/../views/user/scan_result.php';
        return;
    }

    // Check if user already has attendance record for this project
    $stmt = $pdo->prepare(
        "SELECT * FROM drive_attendance
         WHERE user_id = ?
           AND project_id = ?"
    );
    $stmt->execute([$user_id, $project_id]);
    $existingAttendance = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingAttendance) {
        if ($existingAttendance['check_out_time'] === null) {
            $status = 'warning';
            $title = '⚠️ Already Checked In';
            $message = 'You are already checked in to this event. Please scan the checkout QR when you leave.';
        } else {
            $status = 'info';
            $title = 'ℹ️ Attendance Already Recorded';
            $message = 'You have already completed attendance for this event.';
            $points = (int) $existingAttendance['points_earned'];
        }
        require_once __DIR__ . '/../views/user/scan_result.php';
        return;
    }

    // Record check-in
    $stmt = $pdo->prepare(
        "INSERT INTO drive_attendance
         (user_id, project_id, check_in_time)
         VALUES (?, ?, NOW())"
    );
    $stmt->execute([$user_id, $project_id]);

    // status remains 'applied' until checkout

    $status = 'success_checkin';
    require_once __DIR__ . '/../views/user/scan_result.php';
}

/* =========================
   CHECK-OUT
========================= */
function attendance_checkout(PDO $pdo)
{
    require_login('user');

    $token = $_GET['token'] ?? '';

    if ($token === '') {
        $status = 'error';
        $title = 'Invalid Token';
        $message = 'Invalid check-out token.';
        require_once __DIR__ . '/../views/user/scan_result.php';
        return;
    }

    $stmt = $pdo->prepare(
        "SELECT * FROM drive_qr_tokens
         WHERE token = ?
           AND type = 'checkout'
           AND expires_at > NOW()
         LIMIT 1"
    );
    $stmt->execute([$token]);
    $qr = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$qr) {
        $status = 'error';
        $title = 'Token Expired';
        $message = 'This check-out QR is invalid or expired.';
        require_once __DIR__ . '/../views/user/scan_result.php';
        return;
    }

    $user_id = (int) $_SESSION['user_id'];
    $project_id = (int) $qr['project_id'];

    // Find active check-in
    $stmt = $pdo->prepare(
        "SELECT * FROM drive_attendance
         WHERE user_id = ?
           AND project_id = ?
           AND check_out_time IS NULL
         ORDER BY check_in_time DESC
         LIMIT 1"
    );
    $stmt->execute([$user_id, $project_id]);
    $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$attendance) {
        $stmt = $pdo->prepare("SELECT id FROM volunteer_applications WHERE user_id = ? AND project_id = ?");
        $stmt->execute([$user_id, $project_id]);
        $registered = $stmt->fetch();

        $status = 'error';
        if (!$registered) {
            $title = '🚫 Registration Missing';
            $message = "You haven't signed up for this event.";
        } else {
            $title = '⚠️ No Active Check-in';
            $message = "You need to check in first before checking out.";
        }
        require_once __DIR__ . '/../views/user/scan_result.php';
        return;
    }

    // Calculate time spent
    $checkIn = strtotime($attendance['check_in_time']);
    $checkOut = time();

    $hours = floor(($checkOut - $checkIn) / 3600);
    $hours = max(1, $hours); // minimum 1 hour

    $points = $hours * 10;
    $monthYear = date('Y-m');

    try {
        $pdo->beginTransaction();

        // Update attendance
        $stmt = $pdo->prepare(
            "UPDATE drive_attendance
             SET check_out_time = NOW(),
                 points_earned = ?
             WHERE id = ?"
        );
        $stmt->execute([$points, $attendance['id']]);

        // Add points to user (current balance + ranking points)
        $stmt = $pdo->prepare(
            "UPDATE users
             SET points = points + ?,
                 lifetime_points = lifetime_points + ?
             WHERE id = ?"
        );
        $stmt->execute([$points, $points, $user_id]);

        // Add points to monthly leaderboard
        $stmt = $pdo->prepare(
            "INSERT INTO monthly_points (user_id, month_year, points)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE points = points + VALUES(points)"
        );
        $stmt->execute([$user_id, $monthYear, $points]);

        // Update application status to 'completed'
        $stmt = $pdo->prepare(
            "UPDATE volunteer_applications 
             SET status = 'completed' 
             WHERE user_id = ? AND project_id = ?"
        );
        $stmt->execute([$user_id, $project_id]);

        if ($stmt->rowCount() === 0) {
            $stmt = $pdo->prepare("INSERT INTO volunteer_applications (user_id, project_id, status) VALUES (?, ?, 'completed')");
            $stmt->execute([$user_id, $project_id]);
        }

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();
        $status = 'error';
        $title = 'Database Error';
        $message = 'Failed to complete check-out.';
        require_once __DIR__ . '/../views/user/scan_result.php';
        return;
    }

    $status = 'success_checkout';
    require_once __DIR__ . '/../views/user/scan_result.php';
}
