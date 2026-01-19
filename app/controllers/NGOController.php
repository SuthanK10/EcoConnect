<?php
// app/controllers/NGOController.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/NGO.php';
require_once __DIR__ . '/../models/Project.php';

/* =========================
   APPROVAL GUARD
========================= */
function require_approved_ngo(PDO $pdo)
{
    $ngo = ngo_find_by_user($pdo, (int)$_SESSION['user_id']);

    if (!$ngo) {
        die('NGO profile not found.');
    }

    if ($ngo['status'] !== 'approved') {
        die('Your NGO account is pending admin approval.');
    }

    return $ngo;
}

/* =========================
   NGO DASHBOARD
========================= */
function ngo_dashboard(PDO $pdo)
{
    require_login('ngo');

    $ngo = ngo_find_by_user($pdo, (int)$_SESSION['user_id']);
    if (!$ngo) {
        die('NGO profile not found.');
    }

    $projects = project_list_for_ngo($pdo, $ngo['id']);
    
    // Update activity
    ngo_update_activity($pdo, $ngo['id']);

    $pageTitle = 'NGO Dashboard';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/ngo/dashboard.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   GENERATE QR (CHECK-IN / CHECK-OUT)
========================= */
function ngo_generate_qr(PDO $pdo)
{
    require_login('ngo');
    $ngo = require_approved_ngo($pdo);

    $project_id = (int)($_GET['project_id'] ?? 0);
    $type = $_GET['type'] ?? '';

    if (!in_array($type, ['checkin', 'checkout'], true)) {
        die('Invalid QR type.');
    }

    // Ensure project belongs to NGO
    $stmt = $pdo->prepare(
        "SELECT id, title FROM projects WHERE id = ? AND ngo_id = ?"
    );
    $stmt->execute([$project_id, $ngo['id']]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        die('Unauthorized project.');
    }

    // Generate token
    $token = bin2hex(random_bytes(16));
    
    // Delete old tokens for this project (cleanup)
    $stmt = $pdo->prepare(
        "DELETE FROM drive_qr_tokens 
         WHERE project_id = ? AND type = ?"
    );
    $stmt->execute([$project_id, $type]);

    // Calculate expiry (5 minutes from now)
    $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    // Insert new token
    $stmt = $pdo->prepare(
        "INSERT INTO drive_qr_tokens (project_id, type, token, expires_at)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$project_id, $type, $token, $expires]);

    // Update activity
    ngo_update_activity($pdo, $ngo['id']);

    // Verify insertion
    $stmt = $pdo->prepare(
        "SELECT * FROM drive_qr_tokens WHERE token = ?"
    );
    $stmt->execute([$token]);
    $verifyToken = $stmt->fetch(PDO::FETCH_ASSOC);

    // Build URL - Improved for local/mobile testing
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Ensure we get the correct base path even in subdirectories
    $script = $_SERVER['SCRIPT_NAME'];
    $path = rtrim(dirname($script), '/\\');
    
    // Construct the final URL
    $qrUrl = "{$scheme}://{$host}{$path}/index.php?route=attendance_{$type}&token={$token}";

    $pageTitle = 'QR Code';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/ngo/qr_view.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   EDIT NGO PROFILE
========================= */
function ngo_profile_edit(PDO $pdo)
{
    require_login('ngo');

    $ngo = ngo_find_by_user($pdo, (int)$_SESSION['user_id']);
    if (!$ngo) {
        die('NGO profile not found.');
    }

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = sanitize($_POST['name'] ?? '');
        $website = sanitize($_POST['website'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $verification_link = sanitize($_POST['verification_link'] ?? '');
        $whatsapp_link = sanitize($_POST['whatsapp_link'] ?? '');

        if ($name === '') {
            $error = 'NGO name is required.';
        } elseif ($verification_link === '') {
            $error = 'Verification link is required.';
        } else {
            $logo_path = null;
            if (!empty($_FILES['ngo_logo']['name'])) {
                $target_dir = __DIR__ . "/../../public/uploads/logos/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_ext = strtolower(pathinfo($_FILES['ngo_logo']['name'], PATHINFO_EXTENSION));
                $new_name = "logo_" . time() . "_" . $ngo['id'] . "." . $file_ext;
                $target_file = $target_dir . $new_name;

                if (move_uploaded_file($_FILES['ngo_logo']['tmp_name'], $target_file)) {
                    $logo_path = "uploads/logos/" . $new_name;
                }
            }

            ngo_update_profile($pdo, $ngo['id'], $name, $website, $description, $verification_link, $whatsapp_link, $logo_path);
            
            // Update activity
            ngo_update_activity($pdo, $ngo['id']);
            
            $_SESSION['flash_success'] = 'NGO profile updated successfully.';
            header('Location: index.php?route=ngo_dashboard');
            exit;
        }
    }

    $pageTitle = 'Edit NGO Profile';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/ngo/profile_edit.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   CREATE PROJECT
========================= */
function ngo_project_new(PDO $pdo)
{
    require_login('ngo');
    $ngo = require_approved_ngo($pdo);

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $title        = sanitize($_POST['title'] ?? '');
        $description  = sanitize($_POST['description'] ?? '');
        $location     = sanitize($_POST['location'] ?? '');
        $event_date   = $_POST['event_date'] ?? '';
        $start_time   = $_POST['start_time'] ?? '';
        $end_time     = $_POST['end_time'] ?? '';
        $points_reward = (int)($_POST['points_reward'] ?? 0);
        $category     = sanitize($_POST['category'] ?? 'General Cleanup');

        $latitude  = isset($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;

        if (
            $title === '' ||
            $description === '' ||
            $location === '' ||
            $event_date === '' ||
            $start_time === '' ||
            $end_time === '' ||
            $latitude === null ||
            $longitude === null
        ) {
            $error = 'All fields including date, start time, end time and map location are required.';
        } else {
            // Auto-calculate points (10 pts/hr)
            $start = strtotime($start_time);
            $end = strtotime($end_time);
            $diffSeconds = $end - $start;
            if ($diffSeconds < 0) $diffSeconds += 24 * 3600;
            $hours = $diffSeconds / 3600;
            $points_reward = (int)round($hours * 10);

            $image_path = null;

            if (!empty($_FILES['image']['name'])) {
                $target = __DIR__ . '/../../public/uploads/events/';
                if (!is_dir($target)) {
                    mkdir($target, 0777, true);
                }
                $fname = time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $target . $fname);
                $image_path = 'uploads/events/' . $fname;
            }

            project_create_with_image(
                $pdo,
                $ngo['id'],
                $title,
                $description,
                $location,
                $event_date,
                $start_time,
                $end_time,
                $points_reward,
                $image_path,
                $latitude,
                $longitude,
                $category
            );

            // Update activity
            ngo_update_activity($pdo, $ngo['id']);

            // If we were adopting a proposal, mark it as converted
            if (isset($_SESSION['adopting_proposal_id'])) {
                require_once __DIR__ . '/../models/Proposal.php';
                proposal_update_status($pdo, (int)$_SESSION['adopting_proposal_id'], 'converted');
                unset($_SESSION['adopting_proposal_id']);
                unset($_SESSION['adopting_proposal_data']);
            }

            $_SESSION['flash_success'] = 'Cleanup drive publicized successfully!';
            redirect('ngo_dashboard');
        }
    }

    $pageTitle = 'Create Project';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/ngo/project_new.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   EDIT PROJECT
========================= */
function ngo_project_edit(PDO $pdo)
{
    require_login('ngo');
    $ngo = require_approved_ngo($pdo);

    $id = (int)($_GET['id'] ?? 0);

    $stmt = $pdo->prepare(
        "SELECT * FROM projects WHERE id = ? AND ngo_id = ?"
    );
    $stmt->execute([$id, $ngo['id']]);
    $project = $stmt->fetch();

    if (!$project) {
        die('Project not found.');
    }

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $title        = sanitize($_POST['title'] ?? '');
        $description  = sanitize($_POST['description'] ?? '');
        $location     = sanitize($_POST['location'] ?? '');
        $event_date   = $_POST['event_date'] ?? '';
        $start_time   = $_POST['start_time'] ?? '';
        $end_time     = $_POST['end_time'] ?? '';
        $points_reward = (int)($_POST['points_reward'] ?? 0);
        $status       = $_POST['status'] ?? 'open';
        $category     = sanitize($_POST['category'] ?? 'General Cleanup');

        if (
            $title === '' ||
            $description === '' ||
            $location === '' ||
            $event_date === '' ||
            $start_time === '' ||
            $end_time === '' ||
            !isset($_POST['latitude']) || !isset($_POST['longitude'])
        ) {
            $error = 'All fields including map location are required.';
        } else {
            // Auto-calculate points (10 pts/hr)
            $start = strtotime($start_time);
            $end = strtotime($end_time);
            $diffSeconds = $end - $start;
            if ($diffSeconds < 0) $diffSeconds += 24 * 3600;
            $hours = $diffSeconds / 3600;
            $points_reward = (int)round($hours * 10);

            $latitude = (float)$_POST['latitude'];
            $longitude = (float)$_POST['longitude'];
            $image_path = null;

            if (!empty($_FILES['image']['name'])) {
                $target = __DIR__ . '/../../public/uploads/events/';
                if (!is_dir($target)) {
                    mkdir($target, 0777, true);
                }
                $fname = time() . '_' . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target . $fname)) {
                    $image_path = 'uploads/events/' . $fname;
                }
            }

            project_update_full(
                $pdo,
                $id,
                $ngo['id'],
                $title,
                $description,
                $location,
                $event_date,
                $start_time,
                $end_time,
                $points_reward,
                $status,
                $latitude,
                $longitude,
                $category,
                $image_path
            );

            // Update activity
            ngo_update_activity($pdo, $ngo['id']);

            $_SESSION['flash_success'] = 'Project updated successfully.';
            redirect('ngo_dashboard');
        }
    }

    $pageTitle = 'Edit Project';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/ngo/project_edit.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/**
 * Browse Community Suggestions for NGOs
 */
function ngo_proposals(PDO $pdo)
{
    require_login('ngo');
    require_once __DIR__ . '/../models/NGO.php';
    require_once __DIR__ . '/../models/Proposal.php';
    
    $ngo = ngo_find_by_user($pdo, (int)$_SESSION['user_id']);
    $proposals = proposal_list_approved($pdo);
    
    $pageTitle = 'Community Suggestions';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/ngo/proposals.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/**
 * Handle Proposal Adoption
 */
function ngo_adopt_proposal(PDO $pdo)
{
    require_login('ngo');
    require_once __DIR__ . '/../models/Proposal.php';
    
    $id = (int)($_GET['proposal_id'] ?? 0);
    $proposal = proposal_find_by_id($pdo, $id);
    
    if (!$proposal || $proposal['status'] !== 'approved') {
        redirect('ngo_proposals');
    }

    // We store the proposal ID in session to prepopulate the 'new project' form
    $_SESSION['adopting_proposal_id'] = $id;
    $_SESSION['adopting_proposal_data'] = $proposal;
    
    redirect('ngo_project_new');
}