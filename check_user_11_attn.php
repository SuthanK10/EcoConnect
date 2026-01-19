<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->prepare("SELECT * FROM drive_attendance WHERE user_id = 11 AND project_id IN (15, 16)");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "User: " . $row['user_id'] . " | Proj: " . $row['project_id'] . " | Out: " . ($row['check_out_time'] ?? 'NULL') . "\n";
}
