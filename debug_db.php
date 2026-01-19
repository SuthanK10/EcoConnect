<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("DESCRIBE projects");
$fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($fields as $f) {
    if (in_array($f['Field'], ['start_time', 'end_time', 'event_date'])) {
        echo "{$f['Field']} TYPE: {$f['Type']}\n";
    }
}
$stmt = $pdo->query("SELECT CURRENT_TIME as ct, CURRENT_DATE as cd");
$row = $stmt->fetch();
echo "SQL: " . $row['cd'] . " " . $row['ct'] . "\n";
echo "PHP: " . date('Y-m-d H:i:s') . "\n";
?>
