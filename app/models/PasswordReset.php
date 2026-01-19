<?php
// app/models/PasswordReset.php

function password_reset_create(PDO $pdo, string $email, string $token)
{
    // delete old tokens for this email
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->execute([$email]);

    // insert new token (expiry handled by DB DEFAULT)
    $stmt = $pdo->prepare("
        INSERT INTO password_resets (email, token)
        VALUES (?, ?)
    ");
    $stmt->execute([$email, $token]);
}

function password_reset_find_valid(PDO $pdo, string $token)
{
    $stmt = $pdo->prepare("
        SELECT * FROM password_resets
        WHERE token = ?
        LIMIT 1
    ");
    $stmt->execute([$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function password_reset_delete_for_email(PDO $pdo, string $email)
{
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->execute([$email]);
}
