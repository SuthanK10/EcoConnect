<?php
require_once __DIR__ . '/app/config.php';

try {
    // Find all attendance records that have a check-out time
    $stmt = $pdo->query("SELECT user_id, project_id FROM drive_attendance WHERE check_out_time IS NOT NULL");
    $completed = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;
    foreach ($completed as $row) {
        $update = $pdo->prepare("UPDATE volunteer_applications SET status = 'completed' WHERE user_id = ? AND project_id = ? AND status != 'completed'");
        $update->execute([$row['user_id'], $row['project_id']]);
        $count += $update->rowCount();
    }

    echo "Successfully updated $count application(s) to 'completed'.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
