<?php
// app/controllers/HomeController.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/Post.php';

function home_index(PDO $pdo) {
    $pageTitle = 'Home';

    // Get up to 3 upcoming open projects for "Featured Cleanup Events"
    $featured = project_list_featured($pdo, 3);

    // Get up to 2 recent approved posts for the visual showcase
    $recentPosts = post_list_approved_limited($pdo, 2);

    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/home/index.php';
    include __DIR__ . '/../views/layouts/footer.php';
}