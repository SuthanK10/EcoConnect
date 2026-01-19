<?php
require_once __DIR__ . '/app/config.php';
$stmt = $pdo->query("SELECT da.*, u.name as user_name, p.title as project_title 
                     FROM drive_attendance da 
                     JOIN users u ON da.user_id = u.id 
                     JOIN projects p ON da.project_id = p.id");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
?>
