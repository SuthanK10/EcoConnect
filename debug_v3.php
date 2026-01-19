<?php
require_once __DIR__ . '/app/config.php';

$user_id = 11; // Based on previous analysis

echo "--- User 11 Attendance (Completed) ---\n";
$stmt = $pdo->prepare("
    SELECT da.project_id, p.title, p.category, da.check_out_time 
    FROM drive_attendance da
    JOIN projects p ON da.project_id = p.id
    WHERE da.user_id = ? AND da.check_out_time IS NOT NULL
");
$stmt->execute([$user_id]);
$completions = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($completions as $c) {
    echo "ID: {$c['project_id']} | Cat: [{$c['category']}] | Title: {$c['title']} | Out: {$c['check_out_time']}\n";
}

echo "\n--- Favorite Category Detected ---\n";
$stmt = $pdo->prepare("
    SELECT p.category, COUNT(*) as count
    FROM drive_attendance da
    JOIN projects p ON da.project_id = p.id
    WHERE da.user_id = ? AND da.check_out_time IS NOT NULL
    GROUP BY p.category
    HAVING count >= 2
    ORDER BY count DESC
    LIMIT 1
");
$stmt->execute([$user_id]);
$fav = $stmt->fetch(PDO::FETCH_ASSOC);
if ($fav) {
    echo "FAV: [{$fav['category']}] (Count: {$fav['count']})\n";
    
    echo "\n--- Open Projects in this Category ---\n";
    $stmt = $pdo->prepare("
        SELECT id, title, category, status, event_date 
        FROM projects 
        WHERE category = ? AND status = 'open' AND event_date >= CURRENT_DATE
    ");
    $stmt->execute([$fav['category']]);
    $opens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($opens as $o) {
        // Check if user has applied/attended
        $stmt2 = $pdo->prepare("SELECT id FROM volunteer_applications WHERE user_id = ? AND project_id = ?");
        $stmt2->execute([$user_id, $o['id']]);
        $applied = $stmt2->fetch() ? 'YES' : 'NO';
        echo "ID: {$o['id']} | Title: {$o['title']} | Date: {$o['event_date']} | Applied: $applied\n";
    }
} else {
    echo "No favorite category with 2+ completions.\n";
}
