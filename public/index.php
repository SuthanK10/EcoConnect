<?php
// public/index.php

// 1. HTTP Security Headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://maps.googleapis.com https://unpkg.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:;");

// 2. Minimize Information Disclosure
header_remove("X-Powered-By");
ini_set('expose_php', 'off');

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/helpers.php';

// 3. CSRF Protection Initialization
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 4. CSRF Token Validation for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Ensure both session token and post token are non-empty and match
    if (empty($_SESSION['csrf_token']) || $csrf_token === '' || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        // Log the potential attack
        error_log("CSRF token validation failed for route: " . ($_GET['route'] ?? 'home'));
        http_response_code(403);
        die("CSRF token validation failed. Please refresh and try again.");
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/controllers/LeaderboardController.php';

// Get route
$route = $_GET['route'] ?? $_POST['route'] ?? 'home';

switch ($route) {

    /* =========================
       HOME & AUTH
    ========================= */
    case 'home':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        home_index($pdo);
        break;

    case 'login':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        auth_login($pdo);
        break;

    case 'logout':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        auth_logout();
        break;

    case 'submit_appeal':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        auth_submit_appeal($pdo);
        break;

    case 'register':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        auth_register($pdo);
        break;

    case 'forgot_password':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        auth_forgot_password($pdo);
        break;

    case 'reset_password':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        auth_reset_password($pdo);
        break;

    /* =========================
       USER
    ========================= */
    case 'user_dashboard':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        user_dashboard($pdo);
        break;

    case 'user_edit_profile':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        user_edit_profile($pdo);
        break;

    case 'user_my_cleanups':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        user_my_cleanups($pdo);
        break;

    case 'user_propose_cleanup':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        user_propose_cleanup($pdo);
        break;

    case 'user_my_proposals':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        user_my_proposals($pdo);
        break;

    case 'user_delete_proposal':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        user_delete_proposal($pdo);
        break;

    /* =========================
       EVENTS
    ========================= */
    case 'events':
        require_once __DIR__ . '/../app/controllers/EventController.php';
        events_index($pdo);
        break;

    case 'event_show':
        require_once __DIR__ . '/../app/controllers/EventController.php';
        events_show($pdo);
        break;

    /* =========================
       COMMUNITY GALLERY
    ========================= */
    case 'gallery':
        require_once __DIR__ . '/../app/controllers/PostController.php';
        gallery_index($pdo);
        break;

    case 'gallery_store':
        require_once __DIR__ . '/../app/controllers/PostController.php';
        gallery_store($pdo);
        break;

    /* =========================
       NGO
    ========================= */
    case 'ngo_dashboard':
        require_once __DIR__ . '/../app/controllers/NGOController.php';
        ngo_dashboard($pdo);
        break;

    case 'ngo_profile_edit':
        require_once __DIR__ . '/../app/controllers/NGOController.php';
        ngo_profile_edit($pdo);
        break;

    case 'ngo_project_new':
        require_once __DIR__ . '/../app/controllers/NGOController.php';
        ngo_project_new($pdo);
        break;

    case 'ngo_project_edit':
        require_once __DIR__ . '/../app/controllers/NGOController.php';
        ngo_project_edit($pdo);
        break;

    case 'ngo_generate_qr':
        require_once __DIR__ . '/../app/controllers/NGOController.php';
        ngo_generate_qr($pdo);
        break;

    case 'ngo_proposals':
        require_once __DIR__ . '/../app/controllers/NGOController.php';
        ngo_proposals($pdo);
        break;

    case 'ngo_adopt_proposal':
        require_once __DIR__ . '/../app/controllers/NGOController.php';
        ngo_adopt_proposal($pdo);
        break;

    case 'ngo_feedback':
        require_once __DIR__ . '/../app/controllers/NGOController.php';
        ngo_feedback($pdo);
        break;

    /* =========================
       ATTENDANCE (QR)
    ========================= */
    case 'attendance_checkin':
        require_once __DIR__ . '/../app/controllers/AttendanceController.php';
        attendance_checkin($pdo);
        break;

    case 'attendance_checkout':
        require_once __DIR__ . '/../app/controllers/AttendanceController.php';
        attendance_checkout($pdo);
        break;

    case 'feedback':
        require_once __DIR__ . '/../app/controllers/FeedbackController.php';
        feedback_form($pdo);
        break;

    case 'feedback_submit':
        require_once __DIR__ . '/../app/controllers/FeedbackController.php';
        feedback_submit($pdo);
        break;

    /* =========================
       ADMIN
    ========================= */
    case 'admin_dashboard':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_dashboard($pdo);
        break;

    case 'admin_ngos':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_ngos($pdo);
        break;

    case 'admin_projects':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_projects($pdo);
        break;

    case 'admin_project_volunteers':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_project_volunteers($pdo);
        break;

    case 'admin_users':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_users($pdo);
        break;

    case 'admin_user_view':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_user_view($pdo);
        break;

    case 'admin_run_ngo_maintenance':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_run_ngo_maintenance($pdo);
        break;

    case 'admin_run_user_reengagement':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_run_user_reengagement($pdo);
        break;

    case 'admin_appeals':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_appeals($pdo);
        break;

    case 'admin_handle_appeal':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_handle_appeal($pdo);
        break;

    case 'admin_proposals':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_proposals($pdo);
        break;

    case 'admin_handle_proposal':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_handle_proposal($pdo);
        break;

    case 'admin_post_moderation':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_post_moderation($pdo);
        break;

    case 'admin_handle_post':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_handle_post($pdo);
        break;

    case 'admin_finalize_rankings':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        admin_finalize_monthly_rankings($pdo);
        break;

    case 'leaderboard':
        view_leaderboard($pdo);
        break;

    /* =========================
       STATIC
    ========================= */
    case 'donations':
        require_once __DIR__ . '/../app/controllers/DonationController.php';
        donation_index($pdo);
        break;

    case 'donation_success':
        require_once __DIR__ . '/../app/controllers/DonationController.php';
        donation_success($pdo);
        break;


    case 'rewards':
        require_once __DIR__ . '/../app/controllers/RedeemController.php';
        redeem_index($pdo);
        break;

    case 'redeem_action':
        require_once __DIR__ . '/../app/controllers/RedeemController.php';
        redeem_action($pdo);
        break;

    case 'map':
    case 'explore_drives':
        require_once __DIR__ . '/../app/controllers/MapController.php';
        explore_drives($pdo);
        break;

    case 'partnerships':
        require_once __DIR__ . '/../app/controllers/PartnerController.php';
        partnerships_index($pdo);
        break;

    case 'partner_show':
        require_once __DIR__ . '/../app/controllers/PartnerController.php';
        partnerships_show($pdo);
        break;

    /* =========================
       NOTIFICATIONS (AJAX)
    ========================= */
    case 'check_notifications':
        require_once __DIR__ . '/../app/controllers/NotificationController.php';
        ajax_check_reminders($pdo);
        break;

    /* =========================
       MESSAGING
    ========================= */
    case 'messages':
        require_once __DIR__ . '/../app/controllers/MessageController.php';
        message_inbox($pdo);
        break;

    case 'message_chat':
        require_once __DIR__ . '/../app/controllers/MessageController.php';
        message_chat($pdo);
        break;

    case 'message_send':
        require_once __DIR__ . '/../app/controllers/MessageController.php';
        message_send($pdo);
        break;

    case 'contact_ngo':
        require_once __DIR__ . '/../app/controllers/MessageController.php';
        message_contact_ngo($pdo);
        break;

    case 'contact_admin':
        require_once __DIR__ . '/../app/controllers/MessageController.php';
        message_contact_admin($pdo);
        break;

    case 'message_ajax_poll':
        require_once __DIR__ . '/../app/controllers/MessageController.php';
        message_ajax_poll($pdo);
        break;

    /* =========================
       FALLBACK
    ========================= */
    default:
        http_response_code(404);
        echo "Page not found.";
        break;
}
