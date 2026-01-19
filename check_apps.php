<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT * FROM volunteer_applications");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Total Applications: " . count($rows) . "\n";
foreach($rows as $r) {
    echo "User: {$r['user_id']} | Project: {$r['project_id']} | Status: {$r['status']}\n";
}
?>
