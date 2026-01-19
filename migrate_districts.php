<?php
require_once __DIR__ . '/app/config.php';
try {
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM projects LIKE 'district'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE projects ADD COLUMN district VARCHAR(50) DEFAULT 'Colombo' AFTER location");
        echo "District column added.\n";
    } else {
        echo "District column already exists.\n";
    }

    // Update existing projects to have actual districts based on their names/locations if possible
    // For now, let's just make sure some projects are in different districts for testing
    $pdo->exec("UPDATE projects SET district = 'Galle' WHERE location LIKE '%Galle%'");
    $pdo->exec("UPDATE projects SET district = 'Kandy' WHERE location LIKE '%Kandy%'");
    $pdo->exec("UPDATE projects SET district = 'Colombo' WHERE district IS NULL OR district = ''");
    
    echo "Districts updated based on location hints.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
