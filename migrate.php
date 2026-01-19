<?php
// migrate.php
require_once 'app/config.php';
function col_exists($pdo, $table, $col) {
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM $table LIKE '$col'");
        return $stmt->rowCount() > 0;
    } catch (Exception $e) { return false; }
}

try {
    if (!col_exists($pdo, 'ngos', 'logo_path')) {
        $pdo->exec("ALTER TABLE ngos ADD COLUMN logo_path VARCHAR(255) DEFAULT NULL AFTER verification_link");
    }
    if (!col_exists($pdo, 'ngos', 'description')) {
        $pdo->exec("ALTER TABLE ngos ADD COLUMN description TEXT DEFAULT NULL AFTER name");
    }
    if (!col_exists($pdo, 'ngos', 'website')) {
        $pdo->exec("ALTER TABLE ngos ADD COLUMN website VARCHAR(255) DEFAULT NULL AFTER description");
    }
    echo "Migration check complete.\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
