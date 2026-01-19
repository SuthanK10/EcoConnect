<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT * FROM drive_attendance");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($res as $r) {
    echo "ID: {$r['id']} | Proj: {$r['project_id']} | User: {$r['user_id']} | Out: " . ($r['check_out_time'] ?? 'NULL') . "\n";
}
