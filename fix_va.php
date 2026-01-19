<?php
require_once __DIR__ . '/app/config.php';
try {
    echo "Adding reminder_sent column...\n";
    $pdo->exec("ALTER TABLE volunteer_applications ADD COLUMN reminder_sent TINYINT(1) DEFAULT 0");
    echo "Success!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
