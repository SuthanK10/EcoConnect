<?php
require_once __DIR__ . '/app/config.php';

try {
    $pdo->exec("ALTER TABLE community_posts ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER content");
    // Approve existing posts so they don't disappear
    $pdo->exec("UPDATE community_posts SET status = 'approved'");
    echo "Successfully added status column to community_posts and approved existing posts.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column status already exists.\n";
    } else {
        die("Error: " . $e->getMessage());
    }
}
