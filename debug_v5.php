<?php
require_once __DIR__ . '/app/config.php';
$user_id = 11;

$stmt = $pdo->prepare("
    SELECT da.project_id, p.title, p.category 
    FROM drive_attendance da
    JOIN projects p ON da.project_id = p.id
    WHERE da.user_id = ? AND da.check_out_time IS NOT NULL
");
$stmt->execute([$user_id]);
$completions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "COMPLETED DRIVES FOR USER $user_id:\n";
foreach ($completions as $c) {
    echo "- Project #{$c['project_id']}: {$c['title']} | Category: [{$c['category']}]\n";
}
