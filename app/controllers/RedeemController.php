<?php
// app/controllers/RedeemController.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/Reward.php';

function redeem_index(PDO $pdo) {
    $userPoints = 0;
    $myRedemptions = [];
    $user_id = 0;

    if (is_logged_in() && current_user_role() === 'user') {
        $user_id = (int)$_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $userPoints = $stmt->fetchColumn();
        $myRedemptions = reward_list_redemptions($pdo, $user_id);
    }

    $rewards = reward_all($pdo);

    $pageTitle = 'Eco Rewards';
    
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/redeem/index.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function redeem_action(PDO $pdo) {
    require_login('user');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('rewards');
    }

    $user_id = (int)$_SESSION['user_id'];
    $reward_id = (int)($_POST['reward_id'] ?? 0);

    try {
        $code = reward_redeem($pdo, $user_id, $reward_id);
        $_SESSION['success'] = "Redemption successful! Your code is: $code";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    redirect('rewards');
}
