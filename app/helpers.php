<?php
// app/helpers.php

function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function csrf_field() {
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . h($token) . '">';
}

/**
 * Sanitize input for storage (Normalization)
 */
function sanitize($str) {
    if ($str === null) return '';
    // Strip tags to prevent stored HTML
    // We can allow some basic formatting if needed, but for now let's be strict
    return strip_tags(trim($str));
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_user_role() {
    return $_SESSION['role'] ?? null;
}

function require_login($roles = null) {
    if (!is_logged_in()) {
        header('Location: index.php?route=login');
        exit;
    }
    if ($roles !== null) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        $currentRole = current_user_role();
        if (!in_array($currentRole, $roles, true)) {
            http_response_code(403);
            
            // Premium Access Denied Page
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <script src='https://cdn.tailwindcss.com'></script>
                <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap' rel='stylesheet'>
                <style>body { font-family: 'Inter', sans-serif; }</style>
            </head>
            <body class='bg-[#f9fafb] flex items-center justify-center min-h-screen p-4'>
                <div class='max-w-md w-full bg-white rounded-[40px] p-10 shadow-2xl text-center border border-gray-100'>
                    <div class='text-6xl mb-6'>🛡️</div>
                    <h1 class='text-2xl font-black text-[#121613] mb-4'>Access Restricted</h1>
                    <p class='text-sm text-[#677e6b] mb-8 leading-relaxed'>
                        You are currently logged in as a <span class='font-bold text-[#2c4931] uppercase tracking-tighter'>" . h($currentRole) . "</span>. 
                        This area is reserved for <span class='font-bold text-[#2c4931] uppercase tracking-tighter'>" . h(implode(' or ', $roles)) . "</span> accounts only.
                    </p>
                    <div class='space-y-3'>
                        <a href='index.php?route=logout' class='block w-full py-4 rounded-2xl bg-[#2c4931] text-white text-sm font-black tracking-wide hover:bg-[#121613] transition-all'>
                            Sign in with another account
                        </a>
                        <a href='index.php?route=home' class='block w-full py-4 rounded-2xl bg-gray-50 text-[#677e6b] text-sm font-bold hover:bg-gray-100 transition-all'>
                            Back to Home
                        </a>
                    </div>
                </div>
            </body>
            </html>";
            exit;
        }
    }
}

function redirect($route) {
    header("Location: index.php?route=" . urlencode($route));
    exit;
}

/**
 * Validate password strength
 * 8+ chars, uppercase, lowercase, numbers, and symbols
 */
function is_password_strong($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (!preg_match('/[\W]/', $password)) return false; // \W is any non-word character (symbol)
    return true;
}
