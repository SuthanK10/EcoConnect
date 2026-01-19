<?php
// app/controllers/LeaderboardController.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

function view_leaderboard(PDO $pdo)
{
    require_login(); 

    $month = date('Y-m');

    $stmt = $pdo->prepare(
        "SELECT u.name, mp.points
         FROM monthly_points mp
         JOIN users u ON u.id = mp.user_id
         WHERE mp.month_year = ?
         ORDER BY mp.points DESC
         LIMIT 10"
    );
    $stmt->execute([$month]);
    $leaders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch previous finalized month Top 5
    $stmtHistory = $pdo->query(
        "SELECT user_name, points, month_year
         FROM leaderboard_history
         WHERE month_year = (SELECT MAX(month_year) FROM leaderboard_history)
         ORDER BY points DESC
         LIMIT 5"
    );
    $pastWinners = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);

    $lastUpdated = date('F jS Y');
    $pageTitle = "Monthly Leaderboard";

    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/leaderboard/index.php';
    include __DIR__ . '/../views/layouts/footer.php';
}
