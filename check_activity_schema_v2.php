<?php
require_once __DIR__ . '/app/config.php';
function print_schema($pdo, $table) {
    echo "--- $table ---\n";
    $stmt = $pdo->query("DESCRIBE $table");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        printf("%-20s %s\n", $row['Field'], $row['Type']);
    }
}
print_schema($pdo, 'ngos');
print_schema($pdo, 'users');
?>
