<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT id, category FROM projects WHERE id IN (15, 16)");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . " | Cat: [" . $row['category'] . "]\n";
}
