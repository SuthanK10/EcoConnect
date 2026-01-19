<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->prepare("SELECT * FROM drive_attendance WHERE project_id IN (15, 16)");
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (empty($res)) {
    echo "NO ATTENDANCE RECORDS FOUND FOR PROJ 15 OR 16\n";
} else {
    foreach($res as $r) {
        echo "ID: " . $r['id'] . " | User: " . $r['user_id'] . " | Proj: " . $r['project_id'] . " | Out: " . ($r['check_out_time'] ?? 'NULL') . "\n";
    }
}
