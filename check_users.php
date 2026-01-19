<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE users");
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
?>
