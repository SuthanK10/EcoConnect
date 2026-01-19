<?php
// app/models/Attendance.php

/**
 * Create check-in record
 */
function attendance_checkin(PDO $pdo, int $user_id, int $project_id)
{
    // Prevent multiple check-ins without checkout
    $stmt = $pdo->prepare("
        SELECT id FROM attendance
        WHERE user_id = ? AND project_id = ? AND checkout_time IS NULL
        LIMIT 1
    ");
    $stmt->execute([$user_id, $project_id]);

    if ($stmt->fetch()) {
        return false; // already checked in
    }

    $stmt = $pdo->prepare("
        INSERT INTO attendance (user_id, project_id, checkin_time)
        VALUES (?, ?, NOW())
    ");
    $stmt->execute([$user_id, $project_id]);

    return true;
}

/**
 * Perform checkout and award points
 */
function attendance_checkout(PDO $pdo, int $user_id, int $project_id)
{
    $stmt = $pdo->prepare("
        SELECT * FROM attendance
        WHERE user_id = ? AND project_id = ? AND checkout_time IS NULL
        ORDER BY checkin_time DESC
        LIMIT 1
    ");
    $stmt->execute([$user_id, $project_id]);
    $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$attendance) {
        return ['error' => 'No active check-in found.'];
    }

    // Calculate duration in hours
    $checkin = strtotime($attendance['checkin_time']);
    $checkout = time();
    $hours = floor(($checkout - $checkin) / 3600);

    if ($hours <= 0) {
        $hours = 1; // minimum 1 hour
    }

    $points = $hours * 10;

    // Update attendance record
    $stmt = $pdo->prepare("
        UPDATE attendance
        SET checkout_time = NOW(), points_awarded = ?
        WHERE id = ?
    ");
    $stmt->execute([$points, $attendance['id']]);

    // Update user points
    $stmt = $pdo->prepare("
        UPDATE users SET points = points + ?
        WHERE id = ?
    ");
    $stmt->execute([$points, $user_id]);

    return [
        'hours' => $hours,
        'points' => $points
    ];
}
