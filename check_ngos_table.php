<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE ngos");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo $r['Field'] . " (" . $r['Type'] . ")\n";
}
