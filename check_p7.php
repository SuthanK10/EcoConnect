<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT id, title, event_date, start_time FROM projects WHERE id = 7");
print_r($stmt->fetch());
?>
