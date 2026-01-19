<?php
// app/models/User.php

function user_find_by_id(PDO $pdo, int $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function user_find_by_email(PDO $pdo, string $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function user_create(
    PDO $pdo,
    string $name,
    string $email,
    string $password_hash,
    string $role,
    ?string $city = null,
    ?float $latitude = null,
    ?float $longitude = null
) {
    $stmt = $pdo->prepare(
        "INSERT INTO users (name, email, password, role, city, latitude, longitude, is_active)
         VALUES (?, ?, ?, ?, ?, ?, ?, 1)"
    );
    $stmt->execute([$name, $email, $password_hash, $role, $city, $latitude, $longitude]);
    return $pdo->lastInsertId();
}

function user_set_active(PDO $pdo, int $user_id, bool $active) {
    $stmt = $pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
    $stmt->execute([$active ? 1 : 0, $user_id]);
}

/**
 * 🔴 Permanent delete with audit (for login messaging)
 */
function user_delete(PDO $pdo, int $user_id) {

    // Fetch user details BEFORE deletion
    $stmt = $pdo->prepare("SELECT email, role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Store deletion record
        $stmt = $pdo->prepare(
            "INSERT INTO deleted_accounts (email, role) VALUES (?, ?)"
        );
        $stmt->execute([$user['email'], $user['role']]);
    }

    // Delete NGO record if exists
    $stmt = $pdo->prepare("DELETE FROM ngos WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Delete user record
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
}

/**
 * Admin users list
 * (keeps inactive users visible so admin can reactivate)
 */
function user_all_for_admin(PDO $pdo) {
    return $pdo->query("SELECT * FROM users ORDER BY id DESC")
               ->fetchAll(PDO::FETCH_ASSOC);
}
