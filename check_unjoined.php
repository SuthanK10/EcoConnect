<?php
require_once __DIR__ . '/app/config.php';
$user_id = 11;

echo "--- Beach Cleanups User 11 Has NOT Joined ---\n";
$stmt = $pdo->prepare("
    SELECT id, title, category 
    FROM projects 
    WHERE category = 'Beach & Coastal Cleanups' 
      AND status = 'open'
      AND event_date >= CURRENT_DATE
      AND id NOT IN (SELECT project_id FROM volunteer_applications WHERE user_id = ?)
");
$stmt->execute([$user_id]);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . " | " . $row['title'] . "\n";
}
