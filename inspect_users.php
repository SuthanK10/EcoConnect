<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SHOW COLUMNS FROM users");
$cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode(", ", $cols) . "\n";
