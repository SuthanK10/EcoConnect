<?php
// app/controllers/FeedbackController.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

function feedback_form(PDO $pdo) {
    require_login('user');
    $project_id = $_GET['project_id'] ?? 0;
    
    // Verify user actually attended this project
    $stmt = $pdo->prepare("SELECT * FROM drive_attendance WHERE user_id = ? AND project_id = ? AND check_out_time IS NOT NULL");
    $stmt->execute([$_SESSION['user_id'], $project_id]);
    $attendance = $stmt->fetch();
    
    if (!$attendance) {
        die("Invalid project or you haven't completed attendance for this event.");
    }

    // Check if feedback already submitted
    $stmt = $pdo->prepare("SELECT id FROM feedback WHERE user_id = ? AND project_id = ?");
    $stmt->execute([$_SESSION['user_id'], $project_id]);
    if ($stmt->fetch()) {
        $_SESSION['flash_success'] = 'Feedback already submitted.';
        header("Location: index.php?route=user_dashboard");
        exit;
    }

    // Get project details for display
    $stmt = $pdo->prepare("SELECT title FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch();

    require_once __DIR__ . '/../views/user/feedback.php';
}

function feedback_submit(PDO $pdo) {
    require_login('user');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?route=user_dashboard");
        exit;
    }

    $project_id = $_POST['project_id'] ?? 0;
    $event_rating = $_POST['event_rating'] ?? 0;
    $ngo_helpfulness = $_POST['ngo_helpfulness'] ?? 0;
    $comments = sanitize($_POST['comments'] ?? '');

    // Simple validation
    if (!$project_id || !$event_rating || !$ngo_helpfulness) {
        header("Location: index.php?route=feedback&project_id=$project_id&error=Please fill all required fields.");
        exit;
    }

    // Verify attendance again
    $stmt = $pdo->prepare("SELECT id FROM drive_attendance WHERE user_id = ? AND project_id = ? AND check_out_time IS NOT NULL");
    $stmt->execute([$_SESSION['user_id'], $project_id]);
    if (!$stmt->fetch()) {
        die("Unauthorized feedback submission.");
    }

    // Insert feedback
    $stmt = $pdo->prepare("INSERT INTO feedback (user_id, project_id, event_rating, ngo_helpfulness, comments) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $project_id, $event_rating, $ngo_helpfulness, $comments]);

    $_SESSION['flash_success'] = "Thank you for your feedback!";
    header("Location: index.php?route=user_dashboard");
    exit;
}
