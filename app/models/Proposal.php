<?php
// app/models/Proposal.php

function proposal_create(PDO $pdo, int $user_id, string $title, string $description, string $location, ?float $lat, ?float $lng, string $date) {
    $stmt = $pdo->prepare("
        INSERT INTO community_proposals (user_id, title, description, location, latitude, longitude, proposed_date)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    return $stmt->execute([$user_id, $title, $description, $location, $lat, $lng, $date]);
}

function proposal_list_by_user(PDO $pdo, int $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM community_proposals WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function proposal_all(PDO $pdo) {
    $stmt = $pdo->query("
        SELECT p.*, u.name as user_name, u.email as user_email
        FROM community_proposals p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function proposal_list_approved(PDO $pdo) {
    $stmt = $pdo->query("
        SELECT p.*, u.name as user_name
        FROM community_proposals p
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'approved'
        ORDER BY p.created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function proposal_find_by_id(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM community_proposals WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function proposal_update_status(PDO $pdo, int $id, string $status) {
    $stmt = $pdo->prepare("UPDATE community_proposals SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
}
?>
