<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE drive_attendance");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
