<?php
// app/controllers/PostController.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/Post.php';

function gallery_index(PDO $pdo) {
    $posts = post_all_approved($pdo);
    
    // Fetch media for each post
    foreach ($posts as &$post) {
        $post['media'] = post_get_media($pdo, (int)$post['id']);
    }
    unset($post);

    // For the modal/upload part
    $user_id = $_SESSION['user_id'] ?? null;
    $role = $_SESSION['role'] ?? null;
    $project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : null;
    $project_title = null;

    if ($project_id) {
        $stmt = $pdo->prepare("SELECT title FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);
        $project_title = $stmt->fetchColumn();
    }
    
    $pageTitle = 'Eco-Action Feed';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/gallery/index.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

function gallery_store(PDO $pdo) {
    if (!is_logged_in()) {
        redirect('login');
    }

    $user_id = (int)$_SESSION['user_id'];
    $role = $_SESSION['role'];
    
    $content = sanitize($_POST['content'] ?? '');
    $project_id = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    
    $media_items = [];

    if (!empty($_FILES['media']['name'][0])) {
        $target = __DIR__ . '/../../public/uploads/gallery/';
        if (!is_dir($target)) {
            mkdir($target, 0777, true);
        }

        foreach ($_FILES['media']['name'] as $key => $name) {
            if ($_FILES['media']['error'][$key] === UPLOAD_ERR_OK) {
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $fname = time() . '_' . uniqid() . '.' . $ext;
                
                if (move_uploaded_file($_FILES['media']['tmp_name'][$key], $target . $fname)) {
                    $type = 'image';
                    if (in_array(strtolower($ext), ['mp4', 'mov', 'avi'])) {
                        $type = 'video';
                    }
                    $media_items[] = ['path' => 'uploads/gallery/' . $fname, 'type' => $type];
                }
            }
        }
    }

    if ($content !== '' || !empty($media_items)) {
        $ngo_id = null;
        $db_user_id = $user_id;

        if ($role === 'ngo') {
            require_once __DIR__ . '/../models/NGO.php';
            $ngo = ngo_find_by_user($pdo, $user_id);
            if ($ngo) {
                $ngo_id = $ngo['id'];
            }
        }

        $post_id = post_create($pdo, $db_user_id, $ngo_id, $project_id, $content);
        
        foreach ($media_items as $item) {
            post_add_media($pdo, $post_id, $item['path'], $item['type']);
        }

        $_SESSION['flash_success'] = "Moment shared! It will appear in the feed after admin approval. 🌟";
    }

    redirect('gallery');
}
?>
