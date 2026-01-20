<?php
// app/controllers/UserController.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/VolunteerApplication.php';

/* =========================
   USER DASHBOARD
========================= */
function user_dashboard(PDO $pdo)
{
    require_login('user');

    $user_id = (int) $_SESSION['user_id'];

    // Fetch full user record
    $user = user_find_by_id($pdo, $user_id);
    if (!$user) {
        echo "User not found.";
        exit;
    }

    // Fetch points (spendable) and lifetime_points (ranking)
    $stmt = $pdo->prepare("SELECT points, lifetime_points FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $userStats = $stmt->fetch(PDO::FETCH_ASSOC);
    $points = (int)$userStats['points'];
    $lifetimePoints = (int)$userStats['lifetime_points'];

    /* =========================
       MONTHLY RANK CALCULATION
    ========================= */
    $month = date('Y-m');

    $stmt = $pdo->prepare(
        "SELECT user_id
         FROM monthly_points
         WHERE month_year = ?
         ORDER BY points DESC"
    );
    $stmt->execute([$month]);
    $rankedUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $userRank = null;
    foreach ($rankedUsers as $index => $rankedUserId) {
        if ((int) $rankedUserId === $user_id) {
            $userRank = $index + 1; // ranks start from 1
            break;
        }
    }

    // $userRank will be:
    // null  -> Unranked
    // 1..n  -> Ranked position

    // Fetch nearby projects if user has coordinates (exclude already joined)
    $nearbyProjects = [];
    if (!empty($user['latitude']) && !empty($user['longitude'])) {
        require_once __DIR__ . '/../models/Project.php';
        $nearbyProjects = project_find_nearby($pdo, (float)$user['latitude'], (float)$user['longitude'], 5.0, $user_id);
    }

    // Category-based suggestions
    $favCategories = va_get_favorite_categories($pdo, $user_id);
    $categorySuggestions = [];
    $favoriteCategory = null;

    if (!empty($favCategories)) {
        require_once __DIR__ . '/../models/Project.php';
        foreach ($favCategories as $fav) {
            $testCategory = $fav['category'];
            $stmt = $pdo->prepare("
                SELECT p.*, n.name as ngo_name
                FROM projects p
                JOIN ngos n ON p.ngo_id = n.id
                WHERE p.category = ? 
                  AND p.status = 'open'
                  AND p.event_date >= CURRENT_DATE
                  AND p.id NOT IN (SELECT project_id FROM volunteer_applications WHERE user_id = ?)
                ORDER BY p.event_date ASC
                LIMIT 3
            ");
            $stmt->execute([$testCategory, $user_id]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                $categorySuggestions = $results;
                $favoriteCategory = $testCategory;
                break; // Stop at the first category that actually has suggestions
            }
        }
    }

    $pageTitle = 'User Dashboard';

    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/user/dashboard.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   EDIT PROFILE
========================= */
function user_edit_profile(PDO $pdo)
{
    require_login('user');

    $user_id = (int) $_SESSION['user_id'];
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name  = sanitize($_POST['name'] ?? '');
        $city  = sanitize($_POST['city'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $lat   = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $lng   = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;

        if ($name === '') {
            $error = 'Name is required.';
        } else {
            $stmt = $pdo->prepare(
                "UPDATE users SET name = ?, city = ?, phone = ?, latitude = ?, longitude = ? WHERE id = ?"
            );
            $stmt->execute([$name, $city, $phone, $lat, $lng, $user_id]);

            // Update session name
            $_SESSION['name'] = $name;

            $_SESSION['flash_success'] = 'Profile updated successfully.';
            redirect('user_dashboard');
        }
    }

    $user = user_find_by_id($pdo, $user_id);
    if (!$user) {
        echo "User not found.";
        exit;
    }

    $pageTitle = 'Edit Profile';

    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/user/edit_profile.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   MY CLEANUP PARTICIPATION
========================= */
function user_my_cleanups(PDO $pdo)
{
    require_login('user');

    $user_id = (int) $_SESSION['user_id'];

    // List of drives the user applied / attended
    $applications = va_list_for_user($pdo, $user_id);

    $pageTitle = 'My Cleanup Participation';

    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/user/my_cleanups.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   PROPOSE CLEANUP
========================= */
function user_propose_cleanup(PDO $pdo)
{
    require_login('user');
    require_once __DIR__ . '/../models/Proposal.php';

    $user_id = (int)$_SESSION['user_id'];
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $location = sanitize($_POST['location'] ?? '');
        $date = $_POST['date'] ?? '';
        $lat = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
        $lng = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;

        if ($title === '' || $location === '' || $date === '') {
            $error = 'Please fill in required fields (Title, Location, Date).';
        } else {
            if (proposal_create($pdo, $user_id, $title, $description, $location, $lat, $lng, $date)) {
                $_SESSION['flash_success'] = "Thank you! Your cleanup proposal has been submitted for review.";
                redirect('user_my_proposals');
            } else {
                $error = 'Failed to submit proposal. Please try again.';
            }
        }
    }

    $pageTitle = 'Propose a Cleanup';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/user/propose_cleanup.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =========================
   MY PROPOSALS
========================= */
function user_my_proposals(PDO $pdo)
{
    require_login('user');
    require_once __DIR__ . '/../models/Proposal.php';

    $user_id = (int)$_SESSION['user_id'];
    $proposals = proposal_list_by_user($pdo, $user_id);

    $pageTitle = 'My Proposals';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/user/my_proposals.php';
    include __DIR__ . '/../views/layouts/footer.php';
}
