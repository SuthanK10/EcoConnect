<?php
require_once __DIR__ . '/app/config.php';
$user_id = 11;

echo "COMPLETED DRIVES FOR USER $user_id:\n";
$stmt = $pdo->prepare("SELECT p.id, p.title, p.category FROM drive_attendance da JOIN projects p ON da.project_id = p.id WHERE da.user_id = ? AND da.check_out_time IS NOT NULL");
$stmt->execute([$user_id]);
$completed = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($completed as $c) {
    echo "- ID: {$c['id']} ({$c['category']}) -> {$c['title']}\n";
}

$stmt = $pdo->prepare("SELECT p.category, COUNT(*) as count FROM drive_attendance da JOIN projects p ON da.project_id = p.id WHERE da.user_id = ? AND da.check_out_time IS NOT NULL GROUP BY p.category HAVING count >= 2 ORDER BY count DESC LIMIT 1");
$stmt->execute([$user_id]);
$fav = $stmt->fetch(PDO::FETCH_ASSOC);

if($fav) {
    echo "\nDETECTED FAVORITE CATEGORY: " . $fav['category'] . "\n";
    
    echo "SEARCHING FOR RECOMMENDATIONS IN: " . $fav['category'] . "\n";
    $stmt = $pdo->prepare("SELECT id, title FROM projects WHERE category = ? AND status = 'open' AND event_date >= CURRENT_DATE AND id NOT IN (SELECT project_id FROM volunteer_applications WHERE user_id = ?)");
    $stmt->execute([$fav['category'], $user_id]);
    $sugs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($sugs) {
        foreach($sugs as $s) echo "FOUND: {$s['title']} (ID: {$s['id']})\n";
    } else {
        echo "RESULT: No upcoming '{$fav['category']}' drives found that you haven't applied for.\n";
    }
} else {
    echo "\nRESULT: No category has 2+ completed drives.\n";
}
