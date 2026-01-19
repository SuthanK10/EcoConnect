<?php
// app/models/VolunteerApplication.php

function va_user_has_applied(PDO $pdo, int $user_id, int $project_id) {
    $stmt = $pdo->prepare("SELECT id FROM volunteer_applications WHERE user_id = ? AND project_id = ? LIMIT 1");
    $stmt->execute([$user_id, $project_id]);
    return (bool)$stmt->fetch();
}

function va_create(PDO $pdo, int $user_id, int $project_id) {
    $stmt = $pdo->prepare("INSERT INTO volunteer_applications (user_id, project_id, status) VALUES (?, ?, 'applied')");
    $stmt->execute([$user_id, $project_id]);
}

function va_list_for_user(PDO $pdo, int $user_id) {
    $stmt = $pdo->prepare("
        SELECT va.*, p.title, p.location, p.event_date, p.start_time, p.end_time, p.description, n.name as ngo_name,
               (SELECT id FROM feedback WHERE user_id = va.user_id AND project_id = va.project_id LIMIT 1) as feedback_id
        FROM volunteer_applications va
        JOIN projects p ON va.project_id = p.id
        JOIN ngos n ON p.ngo_id = n.id
        WHERE va.user_id = ? 
        ORDER BY p.event_date DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

/**
 * Get applications for events starting within the next hour (60 mins)
 * that haven't had a reminder sent yet.
 */
function va_get_pending_reminders(PDO $pdo, int $user_id) {
    $now = new DateTime('now', new DateTimeZone('Asia/Colombo'));
    $currentDate = $now->format('Y-m-d');
    $currentTime = $now->format('H:i:s');
    
    $oneHourLater = clone $now;
    $oneHourLater->modify('+1 hour');
    $maxDate = $oneHourLater->format('Y-m-d');
    $maxTime = $oneHourLater->format('H:i:s');

    // Case 1: Same Day (e.g., 2:00 PM to 3:00 PM)
    if ($currentDate === $maxDate) {
        $stmt = $pdo->prepare("
            SELECT va.id, p.title, p.start_time, p.event_date
            FROM volunteer_applications va
            JOIN projects p ON va.project_id = p.id
            WHERE va.user_id = ?
              AND va.reminder_sent = 0
              AND p.event_date = ?
              AND p.start_time > ?
              AND p.start_time <= ?
        ");
        $stmt->execute([$user_id, $currentDate, $currentTime, $maxTime]);
    } 
    // Case 2: Midnight Rollover (e.g., 11:30 PM to 12:30 AM)
    else {
        $stmt = $pdo->prepare("
            SELECT va.id, p.title, p.start_time, p.event_date
            FROM volunteer_applications va
            JOIN projects p ON va.project_id = p.id
            WHERE va.user_id = ?
              AND va.reminder_sent = 0
              AND (
                (p.event_date = ? AND p.start_time > ?)
                OR 
                (p.event_date = ? AND p.start_time <= ?)
              )
        ");
        $stmt->execute([$user_id, $currentDate, $currentTime, $maxDate, $maxTime]);
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function va_mark_reminder_sent(PDO $pdo, int $va_id) {
    $stmt = $pdo->prepare("UPDATE volunteer_applications SET reminder_sent = 1 WHERE id = ?");
    $stmt->execute([$va_id]);
}

function va_list_for_project(PDO $pdo, int $project_id) {
    $stmt = $pdo->prepare("
        SELECT va.*, u.name, u.email, u.city 
        FROM volunteer_applications va 
        JOIN users u ON va.user_id = u.id 
        WHERE va.project_id = ? 
        ORDER BY va.applied_at DESC
    ");
    $stmt->execute([$project_id]);
    return $stmt->fetchAll();
}

/**
 * Identify categories the user completes often (min 2)
 */
function va_get_favorite_categories(PDO $pdo, int $user_id) {
    $stmt = $pdo->prepare("
        SELECT p.category, COUNT(*) as count
        FROM drive_attendance da
        JOIN projects p ON da.project_id = p.id
        WHERE da.user_id = ? AND da.check_out_time IS NOT NULL
        GROUP BY p.category
        HAVING count >= 2
        ORDER BY count DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
