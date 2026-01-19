<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT id, title, category FROM projects WHERE id IN (1, 10, 11, 15, 16, 17)");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . " | Cat: [" . $row['category'] . "] | Title: " . $row['title'] . "\n";
}

echo "\n--- User 11 Favorite Category Check ---\n";
$stmt = $pdo->prepare("
    SELECT p.category, COUNT(*) as count
    FROM drive_attendance da
    JOIN projects p ON da.project_id = p.id
    WHERE da.user_id = 11 AND da.check_out_time IS NOT NULL
    GROUP BY p.category
");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Category: [" . $row['category'] . "] | Count: " . $row['count'] . "\n";
}
