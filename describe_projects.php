<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE projects");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
