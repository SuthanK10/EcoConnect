<?php
require_once __DIR__ . '/app/config.php';

echo "--- Project 15 & 16 Category Strings ---\n";
$stmt = $pdo->prepare("SELECT id, category FROM projects WHERE id IN (15, 16)");
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($res as $r) {
    echo "ID {$r['id']}: '{$r['category']}' (len: " . strlen($r['category']) . ")\n";
}

echo "\n--- All attendance for User 11 ---\n";
$stmt = $pdo->prepare("SELECT da.project_id, p.category FROM drive_attendance da JOIN projects p ON da.project_id = p.id WHERE da.user_id = 11");
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($res as $r) {
    echo "Proj #{$r['project_id']}: '{$r['category']}'\n";
}
