<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE volunteer_applications");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
?>
