<?php
require_once __DIR__ . '/app/config.php';
$desc = "Chelsea is a dedicated environmental organization focused on preserving Sri Lanka's beautiful coastlines. Founded in 2023, we empower local communities to take action against plastic pollution through organized beach cleanups, educational workshops, and sustainable waste management advocacy.";
$stmt = $pdo->prepare("UPDATE ngos SET description = ? WHERE id = 1");
$stmt->execute([$desc]);
echo "NGO description updated successfully.\n";
?>
