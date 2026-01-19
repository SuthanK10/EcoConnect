<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESC projects");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
?>
