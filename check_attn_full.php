<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT da.id, da.user_id, da.project_id, p.category, da.check_out_time FROM drive_attendance da JOIN projects p ON da.project_id = p.id");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']} | User: {$row['user_id']} | Proj: {$row['project_id']} | Cat: [{$row['category']}] | Out: " . ($row['check_out_time'] ?? 'NULL') . "\n";
}
