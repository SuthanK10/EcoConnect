<?php
require_once __DIR__ . '/app/config.php';

try {
    // 1. Add lifetime_points column
    $pdo->exec("ALTER TABLE users ADD COLUMN lifetime_points INT NOT NULL DEFAULT 0 AFTER points");
    
    // 2. Initialize lifetime_points with current points
    $pdo->exec("UPDATE users SET lifetime_points = points");
    
    echo "Successfully added lifetime_points column and synced existing data.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column lifetime_points already exists. Skipping.\n";
    } else {
        die("Error: " . $e->getMessage());
    }
}
