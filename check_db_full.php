<?php
// check_db_full.php
require_once 'app/config.php';
function print_table($pdo, $table) {
    try {
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "TABLE: $table\n";
        foreach ($columns as $col) {
            echo "  {$col['Field']} | {$col['Type']}\n";
        }
        echo "\n";
    } catch (Exception $e) { echo "ERROR $table: " . $e->getMessage() . "\n"; }
}
print_table($pdo, 'ngos');
print_table($pdo, 'partners');
print_table($pdo, 'users');
