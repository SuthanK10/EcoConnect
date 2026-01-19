<?php
require_once __DIR__ . '/app/config.php';
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM ngos LIKE 'last_activity_at'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE ngos ADD COLUMN last_activity_at DATETIME DEFAULT CURRENT_TIMESTAMP");
        echo "Column 'last_activity_at' added to 'ngos' table.\n";
    } else {
        echo "Column 'last_activity_at' already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
