<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Project.php';

function explore_drives(PDO $pdo)
{
    $projects = project_all_for_map($pdo);

    $pageTitle = 'Explore Cleanup Drives';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/map/explore.php';
    include __DIR__ . '/../views/layouts/footer.php';
}
