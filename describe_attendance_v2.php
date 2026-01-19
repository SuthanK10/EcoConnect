<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBEdrive_attendance"); // Typo fix
$stmt = $pdo->query("DESCRIBE drive_attendance");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo $r['Field'] . "\n";
}
