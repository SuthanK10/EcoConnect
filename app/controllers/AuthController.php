<?php
// app/controllers/AuthController.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../helpers/MailHelper.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/NGO.php';
require_once __DIR__ . '/../models/PasswordReset.php';

/* =========================
   LOGIN
========================= */
function auth_login(PDO $pdo)
{
    $error = '';
    $ip = $_SERVER['REMOTE_ADDR'];

    // 1. Check if IP is currently blocked
    $stmt = $pdo->prepare("SELECT attempts, last_attempt FROM login_attempts WHERE ip_address = ?");
    $stmt->execute([$ip]);
    $attempt_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($attempt_data && $attempt_data['attempts'] >= 5) {
        $last_attempt = strtotime($attempt_data['last_attempt']);
        $lockout_time = 15 * 60; // 15 minutes
        if (time() - $last_attempt < $lockout_time) {
            $error = 'Too many failed attempts. Please try again in 15 minutes.';
        } else {
            // Reset attempts after lockout period
            $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
            $stmt->execute([$ip]);
            $attempt_data = null;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error === '') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = 'Please fill in all fields.';
        } else {
            $user = user_find_by_email($pdo, $email);

            // User does not exist or wrong password
            if (!$user || !password_verify($password, $user['password'])) {
                // Record failed attempt
                if ($attempt_data) {
                    $stmt = $pdo->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = CURRENT_TIMESTAMP WHERE ip_address = ?");
                    $stmt->execute([$ip]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO login_attempts (ip_address, attempts) VALUES (?, 1)");
                    $stmt->execute([$ip]);
                }

                $error = 'Invalid email or password.';
            }
            // User exists but deactivated
            elseif (!$user['is_active']) {
                $error = 'Your account has been deactivated.';
            }
            // Login success
            else {
                // Clear attempts on success
                $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
                $stmt->execute([$ip]);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];

                if ($user['role'] === 'admin') {
                    redirect('admin_dashboard');
                } elseif ($user['role'] === 'ngo') {
                    redirect('ngo_dashboard');
                } else {
                    redirect('user_dashboard');
                }
            }
        }
    }

    $pageTitle = 'Login';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/auth/login.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   LOGOUT
========================= */
function auth_logout()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }
    header('Location: index.php?route=home');
    exit;
}

/* =========================
   REGISTER
========================= */
function auth_register(PDO $pdo)
{
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';
        $role = $_POST['role'] ?? 'user';

        // NGO-only inputs
        $ngo_name = trim($_POST['ngo_name'] ?? '');
        $verification_link = trim($_POST['verification_link'] ?? '');

        // Volunteer inputs
        $city = trim($_POST['city'] ?? '');
        $lat = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $lng = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;

        if ($name === '' || $email === '' || $password === '' || $confirm === '') {
            $error = 'Please fill in all required fields.';
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email.';
        }
        elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        }
        elseif (!is_password_strong($password)) {
            $error = 'Password is too weak. Use at least 8 characters, including uppercase, lowercase, numbers, and symbols (@#$%^ etc).';
        }
        elseif (!in_array($role, ['user', 'ngo'], true)) {
            $error = 'Invalid role selected.';
        }
        elseif ($role === 'user' && $city === '') {
            $error = 'Please enter your location.';
        }
        elseif ($role === 'user' && ($lat === null || $lng === null)) {
            $error = 'Please provide your location coordinates (using the button) to receive nearby notifications.';
        }
        elseif ($role === 'ngo' && $ngo_name === '') {
            $error = 'Please enter your NGO name.';
        }
        elseif ($role === 'ngo' && $verification_link === '') {
            $error = 'Verification link is required for NGO registration.';
        }
        elseif ($role === 'ngo' && !filter_var($verification_link, FILTER_VALIDATE_URL)) {
            $error = 'Please enter a valid verification URL.';
        }
        elseif (user_find_by_email($pdo, $email)) {
            $error = 'An account with this email already exists.';
        }
        else {
            try {
                $pdo->beginTransaction();
                
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $user_id = user_create($pdo, $name, $email, $hashed, $role, $city, $lat, $lng);

                if ($role === 'ngo') {
                    $logo_path = null;
                    if (!empty($_FILES['ngo_logo']['name'])) {
                        $target_dir = __DIR__ . "/../../public/uploads/logos/";
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0777, true);
                        }
                        $file_ext = strtolower(pathinfo($_FILES['ngo_logo']['name'], PATHINFO_EXTENSION));
                        $new_name = "logo_" . time() . "_" . $user_id . "." . $file_ext;
                        $target_file = $target_dir . $new_name;

                        if (move_uploaded_file($_FILES['ngo_logo']['tmp_name'], $target_file)) {
                            $logo_path = "uploads/logos/" . $new_name;
                        }
                    }
                    ngo_create_basic($pdo, $user_id, $ngo_name, $verification_link, $logo_path);
                }

                $pdo->commit();

                file_put_contents(__DIR__ . '/../../reg_debug.log', date('[Y-m-d H:i:s] ') . "SUCCESS: Register as $role - $email\n", FILE_APPEND);

                if ($role === 'ngo') {
                    $_SESSION['flash_success'] = 'Registration successful! Your NGO profile is now under review. You will be able to host cleanup drives once an administrator approves your account.';
                } else {
                    $_SESSION['flash_success'] = 'Registration successful. You can now log in to start your impact journey.';
                }
                header('Location: index.php?route=login');
                exit;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = 'Registration failed: ' . $e->getMessage();
                file_put_contents(__DIR__ . '/../../reg_debug.log', date('[Y-m-d H:i:s] ') . "FAILURE: Register as $role - $email - Error: " . $e->getMessage() . "\n", FILE_APPEND);
            }
        }
    }

    $pageTitle = 'Register';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/auth/register.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   FORGOT PASSWORD (SECURE)
========================= */
function auth_forgot_password(PDO $pdo)
{
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');

        if ($email !== '') {
            $user = user_find_by_email($pdo, $email);

            if ($user) {
                $token = bin2hex(random_bytes(32));

                // Store token (single-use)
                password_reset_create($pdo, $email, $token);

                // Build absolute URL (works from email)
                $baseUrl =
                    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
                    . '://' . $_SERVER['HTTP_HOST']
                    . dirname($_SERVER['SCRIPT_NAME']);

                $resetLink =
                    rtrim($baseUrl, '/')
                    . '/index.php?route=reset_password&token='
                    . urlencode($token);

                // Send email
                send_password_reset_email($email, $resetLink);
            }

            // Generic message (prevents email enumeration)
            $message =
                'If this email is registered, a password reset link has been sent.';
        }
    }

    $pageTitle = 'Forgot Password';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/auth/forgot.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   RESET PASSWORD
========================= */
function auth_reset_password(PDO $pdo)
{
    $token = $_GET['token'] ?? '';
    $reset = null;

    if ($token !== '') {
        $reset = password_reset_find_valid($pdo, $token);
    }

    if (!$reset) {
        echo "Invalid or expired token.";
        exit;
    }

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        if ($password === '' || $confirm === '') {
            $error = 'Please enter and confirm your new password.';
        }
        elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        }
        elseif (!is_password_strong($password)) {
            $error = 'Password is too weak. Use at least 8 characters, including uppercase, lowercase, numbers, and symbols.';
        }
        else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "UPDATE users SET password = ? WHERE email = ?"
            );
            $stmt->execute([$hashed, $reset['email']]);

            // Invalidate token (single-use)
            password_reset_delete_for_email($pdo, $reset['email']);

            $success = 'Password reset successful. You can now log in.';
        }
    }

    $pageTitle = 'Reset Password';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/auth/reset.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   SUBMIT REACTIVATION APPEAL
========================= */
function auth_submit_appeal(PDO $pdo) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('login');
    }

    require_once __DIR__ . '/../models/Appeal.php';
    
    $user_id = (int)($_POST['user_id'] ?? 0);
    $message = trim($_POST['message'] ?? 'Requesting account reactivation.');

    if ($user_id <= 0) {
        redirect('login');
    }

    if (appeal_create($pdo, $user_id, $message)) {
        $_SESSION['flash_success'] = "Reactivation appeal submitted to admin.";
    } else {
        $_SESSION['flash_error'] = "An appeal is already pending for this account.";
    }

    redirect('login');
}
