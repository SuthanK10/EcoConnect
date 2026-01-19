<?php
require_once __DIR__ . '/app/config.php';
$user_id = 11;

echo "--- User 11 Completion Counts per Category ---\n";
$stmt = $pdo->prepare("
    SELECT p.category, COUNT(*) as count
    FROM drive_attendance da
    JOIN projects p ON da.project_id = p.id
    WHERE da.user_id = ? AND da.check_out_time IS NOT NULL
    GROUP BY p.category
    ORDER BY count DESC
");
$stmt->execute([$user_id]);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Category: [" . $row['category'] . "] | Count: " . $row['count'] . "\n";
}
