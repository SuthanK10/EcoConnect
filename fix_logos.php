<?php
require_once __DIR__ . '/app/config.php';
echo "Fixing partner logo paths...\n";
$pdo->exec("UPDATE partners SET logo = 'assets/pearl.png' WHERE id = 1");
$pdo->exec("UPDATE partners SET logo = 'assets/climate.png' WHERE id = 2");
echo "Done.\n";
?>
