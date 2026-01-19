<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT id, name, email FROM users");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($res as $r) {
    echo "ID {$r['id']}: {$r['name']} ({$r['email']})\n";
}
