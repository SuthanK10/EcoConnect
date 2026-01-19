<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo implode(", ", $tables) . "\n";

foreach($tables as $table) {
    if (strpos($table, 'post') !== false || strpos($table, 'gallery') !== false) {
        echo "\nTable: $table\n";
        $s = $pdo->query("DESCRIBE $table");
        foreach($s->fetchAll(PDO::FETCH_ASSOC) as $col) {
            echo $col['Field'] . " (" . $col['Type'] . ")\n";
        }
    }
}
