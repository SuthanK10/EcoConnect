<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE users");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo $r['Field'] . " (" . $r['Type'] . ")\n";
}
echo "\n--- Last Login Check ---\n";
$stmt = $pdo->query("SELECT id, name, last_login_at FROM users LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
