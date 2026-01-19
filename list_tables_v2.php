<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SHOW TABLES");
foreach($stmt->fetchAll(PDO::FETCH_COLUMN) as $t) {
    echo $t . "\n";
}
