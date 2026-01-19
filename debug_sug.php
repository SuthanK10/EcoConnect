<?php
require_once __DIR__ . '/app/config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 1; // Default to 1 if not logged in (for CLI test)

echo "--- Debugging Recommendations for User ID: $user_id ---\n";

// 1. Check completed drives and their categories
$stmt = $pdo->prepare("
    SELECT p.id, p.title, p.category, da.check_out_time
    FROM drive_attendance da
    JOIN projects p ON da.project_id = p.id
    WHERE da.user_id = ?
");
$stmt->execute([$user_id]);
$completions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Completed Drives found: " . count($completions) . "\n";
foreach ($completions as $c) {
    echo "ID: {$c['id']} | Title: {$c['title']} | Category: {$c['category']} | Check-out: " . ($c['check_out_time'] ?? 'NULL') . "\n";
}

// 2. Check favorite category result
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
    echo "Favorite Category Detected: {$fav['category']} (Count: {$fav['count']})\n";
    
    // 3. Check for available suggestions
    $stmt = $pdo->prepare("
        SELECT p.id, p.title
        FROM projects p
        WHERE p.category = ? 
          AND p.status = 'open'
          AND p.event_date >= CURRENT_DATE
          AND p.id NOT IN (SELECT project_id FROM volunteer_applications WHERE user_id = ?)
    ");
    $stmt->execute([$fav['category'], $user_id]);
    $sugs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Available Suggestions in this category: " . count($sugs) . "\n";
    foreach ($sugs as $s) {
        echo "- {$s['title']} (ID: {$s['id']})\n";
    }
} else {
    echo "No category found with 2+ completed drives.\n";
}
