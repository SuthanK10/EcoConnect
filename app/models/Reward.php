<?php
// app/models/Reward.php

function reward_all(PDO $pdo) {
    $stmt = $pdo->query("SELECT * FROM rewards WHERE is_active = 1 AND stock_count > 0 ORDER BY points_cost ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function reward_find_by_id(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM rewards WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function reward_redeem(PDO $pdo, int $user_id, int $reward_id) {
    $pdo->beginTransaction();
    try {
        // 1. Get Reward
        $reward = reward_find_by_id($pdo, $reward_id);
        if (!$reward || $reward['stock_count'] <= 0) {
            throw new Exception("Reward unavailable.");
        }

        // 2. Get User
        $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $userPoints = $stmt->fetchColumn();

        if ($userPoints < $reward['points_cost']) {
            throw new Exception("Insufficient points.");
        }

        // 3. Deduct Points
        $stmt = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ?");
        $stmt->execute([$reward['points_cost'], $user_id]);

        // 4. Update Stock
        $stmt = $pdo->prepare("UPDATE rewards SET stock_count = stock_count - 1 WHERE id = ?");
        $stmt->execute([$reward_id]);

        // 5. Create Redemption Record
        $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
        $stmt = $pdo->prepare("INSERT INTO user_redemptions (user_id, reward_id, points_spent, redemption_code) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $reward_id, $reward['points_cost'], $code]);

        $pdo->commit();
        return $code;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function reward_list_redemptions(PDO $pdo, int $user_id) {
    $stmt = $pdo->prepare("
        SELECT ur.*, r.title, r.partner_name 
        FROM user_redemptions ur
        JOIN rewards r ON ur.reward_id = r.id
        WHERE ur.user_id = ?
        ORDER BY ur.created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
