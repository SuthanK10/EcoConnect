<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->prepare("SELECT * FROM volunteer_applications WHERE user_id = 10");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
?>
