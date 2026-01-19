<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT id, title, category, status, event_date FROM projects");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo "ID: {$r['id']} | Cat: {$r['category']} | Status: {$r['status']} | Date: {$r['event_date']} | Title: {$r['title']}\n";
}
