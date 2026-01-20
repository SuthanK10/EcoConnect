<?php
// app/controllers/EventController.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/VolunteerApplication.php';
require_once __DIR__ . '/../models/Comment.php';

function events_index(PDO $pdo) {
    $district = trim($_GET['district'] ?? '');
    $category = trim($_GET['category'] ?? '');
    $search = trim($_GET['search'] ?? '');
    $projects = project_list_open($pdo, $district, $search, $category);
    $pageTitle = 'Cleanup Drives';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/events/index.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function events_show(PDO $pdo) {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        redirect('events');
    }

    $project = project_find_by_id($pdo, $id);
    if (!$project) {
        redirect('events');
    }

    // 1. Authorization check (IDOR fix)
    $current_user_id = $_SESSION['user_id'] ?? null;
    $current_role = $_SESSION['role'] ?? null;
    $is_owner = ($current_role === 'ngo' && $project['ngo_id'] == ($current_user_id)); 
    $is_admin = ($current_role === 'admin');

    // If project is not 'open' or has expired, only owner or admin can view
    if (($project['status'] !== 'open' || project_is_past($project)) && !$is_owner && !$is_admin) {
        $_SESSION['flash_error'] = 'This cleanup drive is no longer active or has already ended.';
        redirect('events');
    }

    // Handle Application
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
        // 2. State validation (Server-side enforcement)
        if ($project['status'] !== 'open' || project_is_past($project)) {
            $_SESSION['flash_error'] = 'This event is closed or has already ended.';
        } elseif (!is_logged_in() || current_user_role() !== 'user') {
            $_SESSION['flash_error'] = 'Please log in as a volunteer to apply.';
        } else {
            $user_id = (int)$_SESSION['user_id'];
            if (va_user_has_applied($pdo, $user_id, $id)) {
                $_SESSION['flash_error'] = 'You have already applied for this cleanup drive.';
            } else {
                va_create($pdo, $user_id, $id);
                $_SESSION['flash_success'] = 'You have successfully applied! We will contact you soon.';
            }
        }
        header("Location: index.php?route=event_show&id=$id");
        exit;
    }

    // Handle Comments
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
        if (!is_logged_in()) {
            $_SESSION['flash_error'] = 'You must be logged in to comment.';
        } elseif ($project['status'] !== 'open' && !$is_owner && !$is_admin) {
             $_SESSION['flash_error'] = 'Comments are disabled for this event.';
        } else {
            $comment_text = sanitize($_POST['comment_text'] ?? '');
            if ($comment_text !== '') {
                // Stored XSS: We encode on output in the view, but let's be safe
                comment_create($pdo, $id, (int)$_SESSION['user_id'], $comment_text);
                $_SESSION['flash_success'] = 'Comment posted!';
            }
        }
        header("Location: index.php?route=event_show&id=$id#discussion");
        exit;
    }

    $comments = comment_list_for_event($pdo, $id);
    $pageTitle = 'Cleanup Details';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/events/show.php';
    include __DIR__ . '/../views/layouts/footer.php';
}
