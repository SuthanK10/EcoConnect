<?php
// app/models/NGO.php

/**
 * Find NGO profile using user ID
 */
function ngo_find_by_user(PDO $pdo, int $user_id)
{
    $stmt = $pdo->prepare(
        "SELECT * FROM ngos WHERE user_id = ? LIMIT 1"
    );
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function ngo_find_by_id(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare(
        "SELECT * FROM ngos WHERE id = ? LIMIT 1"
    );
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function ngo_create_basic(
    PDO $pdo,
    int $user_id,
    string $name,
    ?string $verification_link = null,
    ?string $logo_path = null
) {
    $stmt = $pdo->prepare(
        "INSERT INTO ngos (user_id, name, verification_link, logo_path, status)
         VALUES (?, ?, ?, ?, 'pending')"
    );
    $stmt->execute([$user_id, $name, $verification_link, $logo_path]);

    return (int)$pdo->lastInsertId();
}

/**
 * Update NGO profile (editable by NGO)
 */
function ngo_update_profile(
    PDO $pdo,
    int $ngo_id,
    string $name,
    ?string $website,
    ?string $description,
    ?string $verification_link,
    ?string $whatsapp_link = null,
    ?string $logo_path = null
) {
    $sql = "UPDATE ngos SET name = ?, website = ?, description = ?, verification_link = ?, whatsapp_link = ?";
    $params = [$name, $website, $description, $verification_link, $whatsapp_link];

    if ($logo_path !== null) {
        $sql .= ", logo_path = ?";
        $params[] = $logo_path;
    }

    $sql .= " WHERE id = ?";
    $params[] = $ngo_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}

/**
 * Fetch ALL NGOs for admin (legacy use)
 */
function ngo_all_for_admin(PDO $pdo)
{
    $stmt = $pdo->query(
        "SELECT ngos.*, users.email
         FROM ngos
         JOIN users ON ngos.user_id = users.id
         ORDER BY ngos.created_at DESC"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetch NGOs filtered by status (pending / approved)
 */
function ngo_by_status_for_admin(PDO $pdo, string $status)
{
    $stmt = $pdo->prepare(
        "SELECT ngos.*, users.email
         FROM ngos
         JOIN users ON ngos.user_id = users.id
         WHERE ngos.status = ?
         ORDER BY ngos.created_at DESC"
    );
    $stmt->execute([$status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Approve / Reject NGO
 */
function ngo_set_status(PDO $pdo, int $ngo_id, string $status)
{
    $stmt = $pdo->prepare(
        "UPDATE ngos SET status = ? WHERE id = ?"
    );
    $stmt->execute([$status, $ngo_id]);
}

/**
 * Permanently delete NGO (reject OR delete)
 */
function ngo_delete(PDO $pdo, int $ngo_id)
{
    $stmt = $pdo->prepare(
        "DELETE FROM ngos WHERE id = ?"
    );
    $stmt->execute([$ngo_id]);
}
/**
 * Update NGO's last activity timestamp
 */
function ngo_update_activity(PDO $pdo, int $ngo_id)
{
    $stmt = $pdo->prepare(
        "UPDATE ngos SET last_activity_at = NOW() WHERE id = ?"
    );
    $stmt->execute([$ngo_id]);
}
