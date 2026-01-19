<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'app/config.php';
$users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC LIMIT 5")->fetchAll();
foreach($users as $u) {
    echo "ID: {$u['id']}, Name: {$u['name']}, Role: {$u['role']}, Date: {$u['created_at']}\n";
}
