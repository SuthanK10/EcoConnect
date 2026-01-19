<?php
// app/controllers/AdminController.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/NGO.php';
require_once __DIR__ . '/../models/Project.php';

function admin_dashboard(PDO $pdo) {
    require_login('admin');
    $totalUsers = $pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c'] ?? 0;
    $totalNgos = $pdo->query("SELECT COUNT(*) AS c FROM ngos")->fetch()['c'] ?? 0;
    $totalProjects = $pdo->query("SELECT COUNT(*) AS c FROM projects")->fetch()['c'] ?? 0;
    $totalApplications = $pdo->query("SELECT COUNT(*) AS c FROM volunteer_applications")->fetch()['c'] ?? 0;
    
    // Inactive users (> 30 days)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'user' AND is_active = 1 AND last_active_at < ?");
    $stmt->execute([date('Y-m-d H:i:s', strtotime('-30 days'))]);
    $inactiveVolunteers = $stmt->fetchColumn();

    $pageTitle = 'Admin Dashboard';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/dashboard.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function admin_ngos(PDO $pdo) {
    require_login('admin');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ngo_id'], $_POST['action'])) {
        $ngo_id = (int)$_POST['ngo_id'];
        $action = $_POST['action'];

        if ($action === 'approve') {
            ngo_set_status($pdo, $ngo_id, 'approved');
        } elseif ($action === 'reject' || $action === 'delete') {
            ngo_delete($pdo, $ngo_id);
        }
        redirect('admin_ngos');
    }

    $pendingNgos  = ngo_by_status_for_admin($pdo, 'pending');
    $approvedNgos = ngo_by_status_for_admin($pdo, 'approved');
    $pageTitle = 'Manage NGOs';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/ngos.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function admin_projects(PDO $pdo) {
    require_login('admin');
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
        $project_id = (int)$_POST['project_id'];
        if (isset($_POST['status']) && in_array($_POST['status'], ['open', 'closed'], true)) {
            project_set_status($pdo, $project_id, $_POST['status']);
        } elseif (isset($_POST['delete'])) {
            project_delete($pdo, $project_id);
        }
        redirect('admin_projects');
    }
    $projects = project_all_for_admin($pdo);
    $pageTitle = 'Manage Projects';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/projects.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function admin_project_volunteers(PDO $pdo) {
    require_login('admin');
    $project_id = (int)($_GET['id'] ?? 0);
    $project = project_find_by_id($pdo, $project_id);

    if (!$project) {
        $_SESSION['flash_error'] = "Project not found.";
        redirect('admin_projects');
    }

    require_once __DIR__ . '/../models/VolunteerApplication.php';
    $volunteers = va_list_for_project($pdo, $project_id);

    $pageTitle = 'Volunteers: ' . $project['title'];
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/project_volunteers.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function admin_user_view(PDO $pdo) {
    require_login('admin');
    
    $user_id = (int)($_GET['id'] ?? 0);
    $user = user_find_by_id($pdo, $user_id);
    
    if (!$user || $user['role'] === 'admin') {
        $_SESSION['flash_error'] = "User not found or access denied.";
        redirect('admin_users');
    }

    require_once __DIR__ . '/../models/VolunteerApplication.php';
    $drives = va_list_for_user($pdo, $user_id);

    $pageTitle = 'User Details: ' . $user['name'];
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/user_view.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function admin_users(PDO $pdo) {
    require_login('admin');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
        $user_id = (int)$_POST['user_id'];
        $action  = $_POST['action'];

        if ($action === 'deactivate') {
            user_set_active($pdo, $user_id, false);
        } elseif ($action === 'activate') {
            user_set_active($pdo, $user_id, true);
        } elseif ($action === 'delete') {
            user_delete($pdo, $user_id);
        }

        redirect('admin_users');
    }

    $users = user_all_for_admin($pdo);
    $pageTitle = 'Manage Users';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/users.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/* =====================================================
   FINALIZE MONTHLY RANKINGS (NEW – NO EXISTING LOGIC TOUCHED)
===================================================== */
function admin_finalize_monthly_rankings(PDO $pdo)
{
    require_login('admin');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin_dashboard');
    }

    // We finalize the most recent month with activity
    $stmt = $pdo->query("SELECT MAX(month_year) AS latest_month FROM monthly_points");
    $res = $stmt->fetch();
    $month = $res['latest_month'] ?? date('Y-m');

    // Check if already finalized
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM finalized_months WHERE month_year = ?");
    $stmt->execute([$month]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['flash_error'] = "Ranking for {$month} has already been finalized.";
        redirect('admin_dashboard');
    }

    try {
        $pdo->beginTransaction();

        // Mark as finalized FIRST to avoid race conditions or double clicks
        $stmt = $pdo->prepare("INSERT INTO finalized_months (month_year) VALUES (?)");
        $stmt->execute([$month]);

        // Get top 10 users for the month WITH their names for history
        $stmt = $pdo->prepare(
            "SELECT mp.user_id, u.name as user_name, mp.points
             FROM monthly_points mp
             JOIN users u ON mp.user_id = u.id
             WHERE mp.month_year = ?
             ORDER BY mp.points DESC
             LIMIT 10"
        );
        $stmt->execute([$month]);
        $leaders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($leaders as $index => $row) {
            $rank = $index + 1;
            // 1. Save to history
            $stmtHist = $pdo->prepare(
                "INSERT INTO leaderboard_history (user_id, user_name, points, rank, month_year)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmtHist->execute([
                $row['user_id'],
                $row['user_name'],
                $row['points'],
                $rank,
                $month
            ]);

            // 2. Award bonus to lifetime points
            $bonus = 0;
            if ($index === 0) {
                $bonus = 50;        // 🥇 1st place
            } elseif ($index < 5) {
                $bonus = 25;        // 🏅 Top 5
            } elseif ($index < 10) {
                $bonus = 10;        // 🏆 Top 10
            }

            if ($bonus > 0) {
                $stmtBonus = $pdo->prepare(
                    "UPDATE users
                     SET points = points + ?
                     WHERE id = ?"
                );
                $stmtBonus->execute([$bonus, $row['user_id']]);
            }
        }

        // 3. Reset the leaderboard points for everyone for THIS specific month_year
        $stmtReset = $pdo->prepare("UPDATE monthly_points SET points = 0 WHERE month_year = ?");
        $stmtReset->execute([$month]);

        $pdo->commit();

        $_SESSION['flash_success'] = "Monthly rankings for {$month} finalized and reset successfully. History preserved.";

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['flash_error'] = "Failed to finalize monthly rankings: " . $e->getMessage();
    }

    redirect('admin_dashboard');
}

/**
 * Trigger manual cleanup of inactive NGOs
 */
function admin_run_ngo_maintenance(PDO $pdo)
{
    require_login('admin');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin_dashboard');
    }
    require_once __DIR__ . '/../maintenance/ngo_cleanup.php';
    
    $days = 90; // Default threshold
    $count = maintenance_deactivate_inactive_ngos($pdo, $days);
    
    $_SESSION['flash_success'] = "Maintenance complete. Deactivated $count inactive NGOs.";
    redirect('admin_dashboard');
}

/**
 * Trigger manual re-engagement for inactive volunteers
 */
function admin_run_user_reengagement(PDO $pdo)
{
    require_login('admin');
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin_dashboard');
    }
    require_once __DIR__ . '/../maintenance/user_reengagement.php';
    
    $days = 30; // Threshold for inactivity
    $count = maintenance_reengage_inactive_volunteers($pdo, $days);
    
    $_SESSION['flash_success'] = "Re-engagement check complete. Sent emails to $count inactive volunteers.";
    redirect('admin_dashboard');
}

/**
 * Manage Reactivation Appeals
 */
function admin_appeals(PDO $pdo)
{
    require_login('admin');
    require_once __DIR__ . '/../models/Appeal.php';
    
    $pendingAppeals = appeal_list_pending($pdo);
    
    $pageTitle = 'Reactivation Appeals';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/appeals.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/**
 * Handle Reactivation Appeal Action
 */
function admin_handle_appeal(PDO $pdo)
{
    require_login('admin');
    require_once __DIR__ . '/../models/Appeal.php';
    
    $id = (int)($_POST['appeal_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    $appeal = appeal_find_by_id($pdo, $id);
    if (!$appeal) {
        redirect('admin_appeals');
    }

    if ($action === 'approve') {
        // Reactivate user
        $stmt = $pdo->prepare("UPDATE users SET is_active = 1 WHERE id = ?");
        $stmt->execute([$appeal['user_id']]);
        
        // Mark appeal as approved
        appeal_set_status($pdo, $id, 'approved');
        $_SESSION['flash_success'] = "Account reactivated successfully.";
    } elseif ($action === 'reject') {
        // Mark appeal as rejected
        appeal_set_status($pdo, $id, 'rejected');
        $_SESSION['flash_error'] = "Appeal rejected.";
    }

    redirect('admin_appeals');
}

/**
 * Manage Community Proposals
 */
function admin_proposals(PDO $pdo)
{
    require_login('admin');
    require_once __DIR__ . '/../models/Proposal.php';
    
    $proposals = proposal_all($pdo);
    
    $pageTitle = 'Community Proposals';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/proposals.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/**
 * Handle Community Proposal Action
 */
function admin_handle_proposal(PDO $pdo)
{
    require_login('admin');
    require_once __DIR__ . '/../models/Proposal.php';
    
    $id = (int)($_POST['proposal_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'approve') {
        proposal_update_status($pdo, $id, 'approved');
        $_SESSION['flash_success'] = "Proposal approved. It is now visible for NGOs for adoption.";
    } elseif ($action === 'reject') {
        proposal_update_status($pdo, $id, 'rejected');
        $_SESSION['flash_error'] = "Proposal rejected.";
    }

    redirect('admin_proposals');
}
/**
 * Manage Community Post Moderation
 */
function admin_post_moderation(PDO $pdo)
{
    require_login('admin');
    require_once __DIR__ . '/../models/Post.php';
    
    $pendingPosts = post_list_pending($pdo);
    foreach ($pendingPosts as &$post) {
        $post['media'] = post_get_media($pdo, (int)$post['id']);
    }
    unset($post);
    
    $pageTitle = 'Content Moderation';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/admin/post_mod.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/**
 * Handle Post Moderation Action
 */
function admin_handle_post(PDO $pdo)
{
    require_login('admin');
    require_once __DIR__ . '/../models/Post.php';
    
    $id = (int)($_POST['post_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'approve') {
        post_update_status($pdo, $id, 'approved');
        $_SESSION['flash_success'] = "Post approved and added to the Eco-Action Feed.";
    } elseif ($action === 'reject') {
        post_update_status($pdo, $id, 'rejected');
        $_SESSION['flash_error'] = "Post rejected.";
    } elseif ($action === 'delete') {
        post_delete($pdo, $id);
        $_SESSION['flash_error'] = "Post deleted permanently.";
    }

    redirect('admin_post_moderation');
}
