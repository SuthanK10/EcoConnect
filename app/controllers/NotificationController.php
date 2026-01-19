<?php
// app/controllers/NotificationController.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/VolunteerApplication.php';

/**
 * AJAX endpoint to check for upcoming event reminders
 */
function ajax_check_reminders(PDO $pdo)
{
    if (!is_logged_in()) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not logged in']);
        return;
    }

    $user_id = (int)$_SESSION['user_id'];
    $reminders = va_get_pending_reminders($pdo, $user_id);

    // Mark them as sent so we don't notify again
    foreach ($reminders as $r) {
        va_mark_reminder_sent($pdo, $r['id']);
    }

    header('Content-Type: application/json');
    echo json_encode($reminders);
    exit;
}
