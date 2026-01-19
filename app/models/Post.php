<?php
// app/models/Post.php

function post_create(PDO $pdo, ?int $user_id, ?int $ngo_id, ?int $project_id, string $content) {
    $stmt = $pdo->prepare("
        INSERT INTO community_posts (user_id, ngo_id, project_id, content)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $ngo_id, $project_id, $content]);
    return (int)$pdo->lastInsertId();
}

function post_add_media(PDO $pdo, int $post_id, string $media_path, string $media_type) {
    $stmt = $pdo->prepare("
        INSERT INTO community_post_media (post_id, media_path, media_type)
        VALUES (?, ?, ?)
    ");
    return $stmt->execute([$post_id, $media_path, $media_type]);
}

function post_get_media(PDO $pdo, int $post_id) {
    $stmt = $pdo->prepare("SELECT * FROM community_post_media WHERE post_id = ? ORDER BY created_at ASC");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function post_all_approved(PDO $pdo) {
    $stmt = $pdo->query("
        SELECT p.*, 
               u.name as user_name, u.role as user_role,
               n.name as ngo_name,
               pr.title as project_title
        FROM community_posts p
        LEFT JOIN users u ON p.user_id = u.id
        LEFT JOIN ngos n ON p.ngo_id = n.id
        LEFT JOIN projects pr ON p.project_id = pr.id
        WHERE p.status = 'approved'
        ORDER BY p.created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function post_list_approved_limited(PDO $pdo, int $limit) {
    $stmt = $pdo->prepare("
        SELECT p.*, 
               u.name as user_name, u.role as user_role,
               n.name as ngo_name
        FROM community_posts p
        LEFT JOIN users u ON p.user_id = u.id
        LEFT JOIN ngos n ON p.ngo_id = n.id
        WHERE p.status = 'approved'
        ORDER BY p.created_at DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function post_list_pending(PDO $pdo) {
    $stmt = $pdo->query("
        SELECT p.*, 
               u.name as user_name, u.role as user_role,
               n.name as ngo_name,
               pr.title as project_title
        FROM community_posts p
        LEFT JOIN users u ON p.user_id = u.id
        LEFT JOIN ngos n ON p.ngo_id = n.id
        LEFT JOIN projects pr ON p.project_id = pr.id
        WHERE p.status = 'pending'
        ORDER BY p.created_at ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function post_update_status(PDO $pdo, int $id, string $status) {
    $stmt = $pdo->prepare("UPDATE community_posts SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
}

function post_delete(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("DELETE FROM community_posts WHERE id = ?");
    return $stmt->execute([$id]);
}

function post_find_by_id(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM community_posts WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
