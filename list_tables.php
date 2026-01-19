<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SHOW TABLES");
$res = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($res);
