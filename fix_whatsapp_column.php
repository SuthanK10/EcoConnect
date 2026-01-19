<?php
require_once __DIR__ . '/app/config.php';

try {
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM ngos LIKE 'whatsapp_link'");
    $exists = $stmt->fetch();
    
    if (!$exists) {
        $pdo->exec("ALTER TABLE ngos ADD COLUMN whatsapp_link VARCHAR(255) DEFAULT NULL");
        echo "SUCCESS: Column 'whatsapp_link' added to 'ngos' table.\n";
    } else {
        echo "INFO: Column 'whatsapp_link' already exists.\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
