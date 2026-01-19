<?php
require_once __DIR__ . '/app/config.php';

$user_id = 11; 

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

if (!$fav) {
    die("User has not completed 2+ drives in any category.\n");
}

$cat = $fav['category'];
echo "Favorite Category: $cat\n";

echo "Checking ALL projects in this category:\n";
$stmt = $pdo->prepare("SELECT id, title, status, event_date FROM projects WHERE category = ?");
$stmt->execute([$cat]);
$all = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($all as $p) {
    $stmt2 = $pdo->prepare("SELECT id FROM volunteer_applications WHERE user_id = ? AND project_id = ?");
    $stmt2->execute([$user_id, $p['id']]);
    $applied = $stmt2->fetch();
    
    $reason = "";
    if ($applied) $reason .= "[ALREADY APPLIED] ";
    if ($p['status'] !== 'open') $reason .= "[STATUS: {$p['status']}] ";
    if (strtotime($p['event_date']) < strtotime(date('Y-m-d'))) $reason .= "[PAST DATE: {$p['event_date']}] ";
    
    $status = ($reason === "") ? "VALID SUGGESTION" : "BLOCKED: " . trim($reason);
    echo "- ID {$p['id']}: {$p['title']} | $status\n";
}
