<?php
require_once __DIR__ . '/app/config.php';

try {
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'last_active_at'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE users ADD COLUMN last_active_at DATETIME DEFAULT CURRENT_TIMESTAMP");
        echo "Added last_active_at to users table.\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'last_reengagement_email_at'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE users ADD COLUMN last_reengagement_email_at DATETIME DEFAULT NULL");
        echo "Added last_reengagement_email_at to users table.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
