<?php
require_once __DIR__ . '/app/config.php';

$user_id = 10;
$project_id = 2;

// Check if application exists
$stmt = $pdo->prepare("SELECT id FROM volunteer_applications WHERE user_id = ? AND project_id = ?");
$stmt->execute([$user_id, $project_id]);
if (!$stmt->fetch()) {
    // Create it
    $stmt = $pdo->prepare("INSERT INTO volunteer_applications (user_id, project_id, status) VALUES (?, ?, 'completed')");
    $stmt->execute([$user_id, $project_id]);
    echo "Created application record for user $user_id on project $project_id.\n";
} else {
    // Update it
    $stmt = $pdo->prepare("UPDATE volunteer_applications SET status = 'completed' WHERE user_id = ? AND project_id = ?");
    $stmt->execute([$user_id, $project_id]);
    echo "Updated existing application record status to 'completed'.\n";
}

// Also ensure they have a checkout time in attendance so it counts as completed
$stmt = $pdo->prepare("UPDATE drive_attendance SET check_out_time = NOW(), points_earned = 20 WHERE user_id = ? AND project_id = ? AND check_out_time IS NULL");
$stmt->execute([$user_id, $project_id]);
if ($stmt->rowCount() > 0) {
    echo "Recorded missing checkout time for user $user_id.\n";
}
?>
