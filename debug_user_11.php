<?php
require_once __DIR__ . '/app/config.php';

echo "--- User Info ---\n";
// Assuming the user is ID 11 based on the screenshot
$user_id = 11; 

echo "--- Project Categories for 15 and 16 ---\n";
$stmt = $pdo->prepare("SELECT id, title, category FROM projects WHERE id IN (15, 16)");
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($projects as $p) {
    echo "ID: {$p['id']} | Title: {$p['title']} | Category: {$p['category']}\n";
}

echo "\n--- Completed Drives for User 11 ---\n";
$stmt = $pdo->prepare("SELECT da.project_id, p.category, da.check_out_time 
                     FROM drive_attendance da 
                     JOIN projects p ON da.project_id = p.id 
                     WHERE da.user_id = ? AND da.check_out_time IS NOT NULL");
$stmt->execute([$user_id]);
$attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($attendance as $a) {
    echo "Proj ID: {$a['project_id']} | Category: {$a['category']} | Checked Out: {$a['check_out_time']}\n";
}

echo "\n--- Favorite Category Detection ---\n";
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
    echo "FAVORITE: {$fav['category']} (Count: {$fav['count']})\n";
    
    echo "\n--- Potential Suggestions ---\n";
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.category, p.status, p.event_date
        FROM projects p
        WHERE p.category = ? 
          AND p.status = 'open'
          AND p.event_date >= CURRENT_DATE
          AND p.id NOT IN (SELECT project_id FROM volunteer_applications WHERE user_id = ?)
    ");
    $stmt->execute([$fav['category'], $user_id]);
    $sugs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($sugs) {
        foreach ($sugs as $s) {
            echo "ID: {$s['id']} | Title: {$s['title']} | Date: {$s['event_date']} | Cat: {$s['category']}\n";
        }
    } else {
        echo "NO UPCOMING SUGGESTIONS FOUND IN THIS CATEGORY.\n";
    }
} else {
    echo "NO FAVORITE CATEGORY DETECTED (Requires 2+ completions).\n";
}
