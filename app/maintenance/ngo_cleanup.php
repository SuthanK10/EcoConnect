<?php
// app/maintenance/ngo_cleanup.php
// This script deactivates NGOs that have been inactive for more than X days.

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/NGO.php';

function maintenance_deactivate_inactive_ngos(PDO $pdo, int $days_threshold = 90) {
    // We want to find NGOs where last_activity_at is older than the threshold
    // AND the user account is currently active.
    
    $stmt = $pdo->prepare("
        SELECT n.id, n.user_id, n.name, n.last_activity_at 
        FROM ngos n
        JOIN users u ON n.user_id = u.id
        WHERE u.is_active = 1
          AND n.last_activity_at < DATE_SUB(NOW(), INTERVAL ? DAY)
    ");
    
    $stmt->execute([$days_threshold]);
    $inactiveNgos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $count = 0;
    foreach ($inactiveNgos as $ngo) {
        // Deactivate the user account
        $update = $pdo->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
        $update->execute([$ngo['user_id']]);
        
        // Log the action (we could have a logs table, but for now just echo/log to file)
        error_log("Automatically deactivated inactive NGO: {$ngo['name']} (Last Activity: {$ngo['last_activity_at']})");
        $count++;
    }
    
    return $count;
}

// If running from CLI
if (php_sapi_name() === 'cli' || isset($_GET['run_maintenance'])) {
    $days = isset($_GET['days']) ? (int)$_GET['days'] : 90;
    $deactivatedCount = maintenance_deactivate_inactive_ngos($pdo, $days);
    echo "Maintenance complete. Deactivated $deactivatedCount inactive NGOs.\n";
}
?>
