<?php
// app/controllers/MessageController.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

function message_inbox(PDO $pdo) {
    require_login();
    $user_id = (int)$_SESSION['user_id'];
    $role = $_SESSION['role'];

    // For simplicity, we'll list all unique users we've had conversations with
    $stmt = $pdo->prepare("
        SELECT DISTINCT u.id, u.name, u.role, 
        (SELECT m.message FROM messages m 
         WHERE (m.sender_id = u.id AND m.receiver_id = ?) 
            OR (m.sender_id = ? AND m.receiver_id = u.id) 
         ORDER BY m.created_at DESC LIMIT 1) as last_msg,
        (SELECT m.created_at FROM messages m 
         WHERE (m.sender_id = u.id AND m.receiver_id = ?) 
            OR (m.sender_id = ? AND m.receiver_id = u.id) 
         ORDER BY m.created_at DESC LIMIT 1) as last_date
        FROM users u
        INNER JOIN messages m ON (m.sender_id = u.id OR m.receiver_id = u.id)
        WHERE (m.sender_id = ? OR m.receiver_id = ?) AND u.id != ?
        ORDER BY last_date DESC
    ");
    $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id]);
    $conversations = $stmt->fetchAll();

    $pageTitle = 'Messages';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/messages/inbox.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function message_chat(PDO $pdo) {
    require_login();
    $my_id = (int)$_SESSION['user_id'];
    $other_id = (int)($_GET['with'] ?? 0);

    if (!$other_id) {
        redirect('messages');
    }

    // Get other user info
    $stmt = $pdo->prepare("SELECT id, name, role FROM users WHERE id = ?");
    $stmt->execute([$other_id]);
    $other_user = $stmt->fetch();

    if (!$other_user) {
        die("User not found.");
    }

    // Mark as read
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
    $stmt->execute([$other_id, $my_id]);

    // Fetch messages
    $stmt = $pdo->prepare("
        SELECT * FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) 
           OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY created_at ASC
    ");
    $stmt->execute([$my_id, $other_id, $other_id, $my_id]);
    $messages = $stmt->fetchAll();

    $pageTitle = 'Chat with ' . $other_user['name'];
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/messages/chat.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function message_send(PDO $pdo) {
    require_login();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

    $sender_id = (int)$_SESSION['user_id'];
    $receiver_id = (int)$_POST['receiver_id'];
    $content = trim($_POST['message']);

    if ($content !== '') {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$sender_id, $receiver_id, $content]);
    }

    // Redirect back to chat
    header("Location: index.php?route=message_chat&with=" . $receiver_id);
    exit;
}

function message_contact_ngo(PDO $pdo) {
    require_login('user');
    
    // Fetch all approved NGOs
    $stmt = $pdo->prepare("SELECT u.id as user_id, n.name, n.logo_path FROM ngos n JOIN users u ON n.user_id = u.id WHERE n.status = 'approved'");
    $stmt->execute();
    $ngos = $stmt->fetchAll();

    $pageTitle = 'Contact NGO';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/messages/contact_ngo.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function message_contact_admin(PDO $pdo) {
    require_login(['ngo', 'user']);
    
    // Find admin user ID
    $stmt = $pdo->prepare("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch();

    if (!$admin) {
        die("Admin account not found.");
    }

    header("Location: index.php?route=message_chat&with=" . $admin['id']);
    exit;
}

function message_ajax_poll(PDO $pdo) {
    require_login();
    $my_id = (int)$_SESSION['user_id'];
    $other_id = (int)$_GET['with'];

    $stmt = $pdo->prepare("
        SELECT * FROM messages 
        WHERE sender_id = ? AND receiver_id = ? AND is_read = 0 
        ORDER BY created_at ASC
    ");
    $stmt->execute([$other_id, $my_id]);
    $new_messages = $stmt->fetchAll();

    // Mark as read
    if ($new_messages) {
        $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
        $stmt->execute([$other_id, $my_id]);
    }

    header('Content-Type: application/json');
    echo json_encode($new_messages);
    exit;
}
