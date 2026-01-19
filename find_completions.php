<?php
require_once __DIR__ . '/app/config.php';

echo "--- Who completed Project 15 and 16? ---\n";
$stmt = $pdo->query("SELECT * FROM drive_attendance WHERE project_id IN (15, 16)");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($res as $r) {
    echo "ID: {$r['id']} | Proj: {$r['project_id']} | User: {$r['user_id']} | Out: " . ($r['check_out_time'] ?? 'NULL') . "\n";
}
