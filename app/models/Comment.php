<?php
// app/models/Comment.php

function comment_list_for_event(PDO $pdo, int $project_id) {
    $sql = "SELECT c.*, u.name AS user_name
            FROM event_comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.project_id = ?
            ORDER BY c.created_at ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$project_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function comment_create(PDO $pdo, int $project_id, int $user_id, string $comment_text) {
    $stmt = $pdo->prepare("INSERT INTO event_comments (project_id, user_id, comment_text) VALUES (?, ?, ?)");
    $stmt->execute([$project_id, $user_id, $comment_text]);
    return (int)$pdo->lastInsertId();
}
