<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT id, title, category, status, event_date FROM projects");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . " | Cat: [" . $row['category'] . "] | Status: " . $row['status'] . " | Date: " . $row['event_date'] . " | Title: " . $row['title'] . "\n";
}

echo "\n--- Attendance ---\n";
$stmt = $pdo->query("SELECT * FROM drive_attendance");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "User: " . $row['user_id'] . " | Proj: " . $row['project_id'] . " | Check-out: " . ($row['check_out_time'] ?? 'NULL') . "\n";
}

echo "\n--- Applications ---\n";
$stmt = $pdo->query("SELECT * FROM volunteer_applications");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "User: " . $row['user_id'] . " | Proj: " . $row['project_id'] . "\n";
}
