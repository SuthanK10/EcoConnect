<?php
require_once __DIR__ . '/app/config.php';

echo "--- Existing Projects and Categories ---\n";
$stmt = $pdo->query("SELECT id, title, category, status, event_date FROM projects ORDER BY id DESC LIMIT 20");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']} | Category: [{$row['category']}] | Status: {$row['status']} | Date: {$row['event_date']} | Title: {$row['title']}\n";
}

echo "\n--- Recent Attendance Records ---\n";
$stmt = $pdo->query("SELECT da.user_id, da.project_id, da.check_out_time, p.category 
                     FROM drive_attendance da 
                     JOIN projects p ON da.project_id = p.id 
                     ORDER BY da.id DESC LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "User: {$row['user_id']} | Proj: {$row['project_id']} | Cat: [{$row['category']}] | Out: " . ($row['check_out_time'] ?? 'NULL') . "\n";
}
