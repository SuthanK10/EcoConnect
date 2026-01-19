<?php
// migrate_history.php
$pdo = new PDO('mysql:host=localhost;dbname=eco-connect', 'root', '');
$pdo->exec("CREATE TABLE IF NOT EXISTS leaderboard_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    user_name VARCHAR(255),
    points INT,
    rank INT,
    month_year VARCHAR(7),
    finalized_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "Table leaderboard_history ready.";
