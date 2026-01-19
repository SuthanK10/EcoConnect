<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'app/config.php';
echo "TOTAL USERS: " . $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() . "\n";
echo "LATEST USER:\n";
print_r($pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 1")->fetch());
echo "\nTOTAL NGOs: " . $pdo->query("SELECT COUNT(*) FROM ngos")->fetchColumn() . "\n";
