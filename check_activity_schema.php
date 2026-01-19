<?php
require_once __DIR__ . '/app/config.php';
echo "NGOs Table:\n";
$stmt = $pdo->query("DESCRIBE ngos");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
echo "\nUsers Table:\n";
$stmt = $pdo->query("DESCRIBE users");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
