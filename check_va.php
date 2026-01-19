<?php
require_once __DIR__ . '/app/config.php';
try {
    $stmt = $pdo->query("DESCRIBE volunteer_applications");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Columns: " . implode(", ", $cols) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
