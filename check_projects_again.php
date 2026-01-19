<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE projects");
$cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $cols);
?>
