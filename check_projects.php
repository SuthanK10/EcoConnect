<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE projects");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($cols as $c) {
    echo $c['Field'] . " (" . $c['Type'] . ")\n";
}
?>
