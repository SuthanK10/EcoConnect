<?php
require_once __DIR__ . '/app/config.php';
$user_id = 1; // Testing for user 1
echo "Checking User ID: $user_id\n";
echo "SQL Time: " . $pdo->query("SELECT CURRENT_TIME")->fetchColumn() . "\n";
echo "SQL Date: " . $pdo->query("SELECT CURRENT_DATE")->fetchColumn() . "\n";

$sql = "
    SELECT va.id, p.title, p.start_time, p.event_date, va.reminder_sent
    FROM volunteer_applications va
    JOIN projects p ON va.project_id = p.id
    WHERE va.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($results as $r) {
    $now = date('H:i:s');
    $plus1 = date('H:i:s', strtotime('+1 hour'));
    $match = ($r['event_date'] == date('Y-m-d') && $r['start_time'] > $now && $r['start_time'] <= $plus1);
    echo "Event: " . $r['title'] . "\n";
    echo " - Date: " . $r['event_date'] . " (Match: " . ($r['event_date'] == date('Y-m-d') ? 'YES':'NO') . ")\n";
    echo " - Time: " . $r['start_time'] . " (Range check: $now < " . $r['start_time'] . " <= $plus1 -> " . ($match ? 'YES':'NO') . ")\n";
    echo " - Already Sent: " . $r['reminder_sent'] . "\n";
}
?>
