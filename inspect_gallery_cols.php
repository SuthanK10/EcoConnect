<?php
require_once __DIR__ . '/app/config.php';
$table = 'community_posts';
echo "Table: $table\n";
$s = $pdo->query("DESCRIBE `community_posts` text"); // No, just DESCRIBE community_posts
$s = $pdo->query("DESCRIBE community_posts");
foreach($s->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}
