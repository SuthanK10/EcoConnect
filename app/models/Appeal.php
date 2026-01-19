<?php
// app/models/Appeal.php

function appeal_create(PDO $pdo, int $user_id, string $message) {
    // Check if a pending appeal already exists
    $stmt = $pdo->prepare("SELECT id FROM reactivation_appeals WHERE user_id = ? AND status = 'pending' LIMIT 1");
    $stmt->execute([$user_id]);
    if ($stmt->fetch()) {
        return false; // Appeal already pending
    }

    $stmt = $pdo->prepare("INSERT INTO reactivation_appeals (user_id, message) VALUES (?, ?)");
    return $stmt->execute([$user_id, $message]);
}

function appeal_list_pending(PDO $pdo) {
    $stmt = $pdo->query("
        SELECT a.*, u.name, u.email, u.role
        FROM reactivation_appeals a
        JOIN users u ON a.user_id = u.id
        WHERE a.status = 'pending'
        ORDER BY a.created_at ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function appeal_set_status(PDO $pdo, int $appeal_id, string $status) {
    $stmt = $pdo->prepare("UPDATE reactivation_appeals SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $appeal_id]);
}

function appeal_find_by_id(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM reactivation_appeals WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
