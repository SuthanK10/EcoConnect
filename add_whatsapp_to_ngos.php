<?php
require_once __DIR__ . '/app/config.php';

try {
    $pdo->exec("ALTER TABLE ngos ADD COLUMN whatsapp_link VARCHAR(255) DEFAULT NULL");
    echo "Successfully added whatsapp_link to ngos table.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
