<?php
require_once __DIR__ . '/app/config.php';
$pdo->exec("UPDATE users SET last_active_at = '2025-12-01 12:00:00' WHERE role = 'user' LIMIT 1");
echo "Updated one volunteer to be inactive for testing.\n";
