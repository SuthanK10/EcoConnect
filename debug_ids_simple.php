<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'app/config.php';
$partners = $pdo->query("SELECT id, name FROM partners")->fetchAll();
$ngos = $pdo->query("SELECT id, name FROM ngos")->fetchAll();
foreach($partners as $p) echo "PARTNER: {$p['id']} - {$p['name']}\n";
foreach($ngos as $n) echo "NGO: {$n['id']} - {$n['name']}\n";
