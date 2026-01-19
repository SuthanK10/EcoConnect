<?php
// Define needed arrays/variables usually in $_SERVER for CLI
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['HTTPS'] = 'off';
$_SERVER['SCRIPT_NAME'] = '/Eco-Connect/debug_ids.php';

require_once 'app/config.php';

echo "PARTNERS:\n";
$stmt = $pdo->query("SELECT id, name FROM partners");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\nNGOs:\n";
$stmt = $pdo->query("SELECT id, name FROM ngos");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
