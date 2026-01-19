<?php
// app/controllers/PartnerController.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/Partner.php';
require_once __DIR__ . '/../models/NGO.php';

function partnerships_index(PDO $pdo) {
    $partners = partner_all($pdo);
    $ngos = ngo_by_status_for_admin($pdo, 'approved');
    
    $pageTitle = 'NGOs & Partners';
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/partnerships/index.php';
    include __DIR__ . '/../views/layouts/footer.php';
}

/**
 * Public NGO Profile & Impact History
 */
function partnerships_show(PDO $pdo) {
    require_once __DIR__ . '/../models/Project.php';
    
    $id = (int)($_GET['id'] ?? 0);
    $type = $_GET['type'] ?? 'ngo'; // default to ngo for backward compatibility

    if ($type === 'partner') {
        $partner = partner_find_by_id($pdo, $id);
        if (!$partner) {
            redirect('partnerships');
        }
        $pageTitle = $partner['name'];
        include __DIR__ . '/../views/layouts/header.php';
        include __DIR__ . '/../views/partnerships/show_partner.php';
        include __DIR__ . '/../views/layouts/footer.php';
        return;
    }

    // Default: NGO Logic
    $ngo = ngo_find_by_id($pdo, $id);
    if (!$ngo || $ngo['status'] !== 'approved') {
        redirect('partnerships');
    }

    $completed_drives = project_list_completed_for_ngo($pdo, $id);
    
    $pageTitle = $ngo['name'];
    include __DIR__ . '/../views/layouts/header.php';
    include __DIR__ . '/../views/partnerships/show.php';
    include __DIR__ . '/../views/layouts/footer.php';
}
