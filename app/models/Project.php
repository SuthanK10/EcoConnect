<?php
// app/models/Project.php

function project_create(
    PDO $pdo,
    int $ngo_id,
    string $title,
    string $description,
    string $location,
    string $event_date,
    int $points_reward
) {
    $stmt = $pdo->prepare("
        INSERT INTO projects
        (ngo_id, title, description, location, event_date, points_reward, status)
        VALUES (?, ?, ?, ?, ?, ?, 'open')
    ");
    $stmt->execute([
        $ngo_id,
        $title,
        $description,
        $location,
        $event_date,
        $points_reward
    ]);

    return (int)$pdo->lastInsertId();
}

function project_update(
    PDO $pdo,
    int $id,
    int $ngo_id,
    string $title,
    string $description,
    string $location,
    string $event_date,
    string $start_time,
    string $end_time,
    int $points_reward,
    string $status
) {
    $stmt = $pdo->prepare("
        UPDATE projects SET
            title = ?,
            description = ?,
            location = ?,
            event_date = ?,
            start_time = ?,
            end_time = ?,
            points_reward = ?,
            status = ?
        WHERE id = ? AND ngo_id = ?
    ");

    $stmt->execute([
        $title,
        $description,
        $location,
        $event_date,
        $start_time,
        $end_time,
        $points_reward,
        $status,
        $id,
        $ngo_id
    ]);
}

function project_update_full(
    PDO $pdo,
    int $id,
    int $ngo_id,
    string $title,
    string $description,
    string $location,
    string $event_date,
    string $start_time,
    string $end_time,
    int $points_reward,
    string $status,
    float $latitude,
    float $longitude,
    string $category,
    ?string $image_path = null
) {
    $sql = "UPDATE projects SET 
            title = ?, 
            description = ?, 
            location = ?, 
            event_date = ?, 
            start_time = ?, 
            end_time = ?, 
            points_reward = ?, 
            status = ?,
            latitude = ?,
            longitude = ?,
            category = ?";
    
    $params = [
        $title, $description, $location, $event_date, 
        $start_time, $end_time, $points_reward, $status,
        $latitude, $longitude, $category
    ];

    if ($image_path !== null) {
        $sql .= ", image_path = ?";
        $params[] = $image_path;
    }

    $sql .= " WHERE id = ? AND ngo_id = ?";
    $params[] = $id;
    $params[] = $ngo_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}



function project_find_by_id(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare("
        SELECT
            p.*,
            n.name AS ngo_name,
            n.description AS ngo_description,
            n.whatsapp_link
        FROM projects p
        JOIN ngos n ON p.ngo_id = n.id
        WHERE p.id = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Projects for map (Explore Cleanup Drives)
 */
function project_all_for_map(PDO $pdo)
{
    $stmt = $pdo->query("
        SELECT
            p.id,
            p.title,
            p.event_date,
            p.location,
            p.latitude,
            p.longitude,
            p.start_time,
            p.image_path,
            n.name AS ngo_name
        FROM projects p
        JOIN ngos n ON p.ngo_id = n.id
        WHERE p.status = 'open'
          AND n.status = 'approved'
          AND (p.event_date > CURRENT_DATE OR (p.event_date = CURRENT_DATE AND (p.end_time IS NULL OR p.end_time >= CURRENT_TIME)))
          AND p.latitude IS NOT NULL
          AND p.longitude IS NOT NULL
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * List open projects for Events page (Filtered)
 */
function project_list_open(PDO $pdo, ?string $district = null, ?string $search = null, ?string $category = null)
{
    $sql = "
        SELECT p.*, n.name AS ngo_name
        FROM projects p
        JOIN ngos n ON p.ngo_id = n.id
        WHERE p.status = 'open'
          AND (p.event_date > CURRENT_DATE OR (p.event_date = CURRENT_DATE AND (p.end_time IS NULL OR p.end_time >= CURRENT_TIME)))
    ";
    $params = [];

    if ($district !== null && $district !== '' && $district !== 'All Districts') {
        $sql .= " AND p.district = ?";
        $params[] = $district;
    }

    if ($category !== null && $category !== '' && $category !== 'All Categories') {
        $sql .= " AND p.category = ?";
        $params[] = $category;
    }

    if ($search !== null && $search !== '') {
        $sql .= " AND (p.title LIKE ? OR p.location LIKE ?)";
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }

    $sql .= " ORDER BY p.event_date ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Featured projects for Home page
 */
function project_list_featured(PDO $pdo, int $limit = 3)
{
    $sql = "
        SELECT p.*, n.name AS ngo_name
        FROM projects p
        JOIN ngos n ON p.ngo_id = n.id
        WHERE p.status = 'open'
          AND (p.event_date > CURRENT_DATE OR (p.event_date = CURRENT_DATE AND (p.end_time IS NULL OR p.end_time >= CURRENT_TIME)))
        ORDER BY p.event_date ASC
        LIMIT :limit
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function project_list_for_ngo(PDO $pdo, int $ngo_id)
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM projects
        WHERE ngo_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$ngo_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function project_all_for_admin(PDO $pdo)
{
    $stmt = $pdo->query("
        SELECT p.*, n.name AS ngo_name
        FROM projects p
        JOIN ngos n ON p.ngo_id = n.id
        ORDER BY p.created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function project_set_status(PDO $pdo, int $id, string $status)
{
    $stmt = $pdo->prepare("
        UPDATE projects
        SET status = ?
        WHERE id = ?
    ");
    $stmt->execute([$status, $id]);
}

function project_delete(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
}

/**
 * ✅ UPDATED: Create project with image + coordinates
 */
function project_create_with_image(
    PDO $pdo,
    int $ngo_id,
    string $title,
    string $description,
    string $location,
    string $event_date,
    string $start_time,
    string $end_time,
    int $points_reward,
    ?string $image_path,
    float $latitude,
    float $longitude,
    string $category = 'General Cleanup'
) {
    $stmt = $pdo->prepare("
        INSERT INTO projects (
            ngo_id,
            title,
            description,
            location,
            event_date,
            start_time,
            end_time,
            points_reward,
            status,
            image_path,
            latitude,
            longitude,
            category
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, 'open', ?, ?, ?, ?
        )
    ");

    $stmt->execute([
        $ngo_id,
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
    ]);

    return (int)$pdo->lastInsertId();
}

/**
 * Find nearby open projects based on coordinates, optionally excluding projects the user has already joined
 */
function project_find_nearby(PDO $pdo, float $lat, float $lng, float $radius_km = 5.0, ?int $exclude_user_id = null)
{
    $sql = "
        SELECT 
            p.*, 
            n.name AS ngo_name,
            (
                6371 * acos(
                    cos(radians(:lat)) * cos(radians(p.latitude)) * 
                    cos(radians(p.longitude) - radians(:lng)) + 
                    sin(radians(:lat)) * sin(radians(p.latitude))
                )
            ) AS distance
        FROM projects p
        JOIN ngos n ON p.ngo_id = n.id
        WHERE p.status = 'open'
          AND (p.event_date > CURRENT_DATE OR (p.event_date = CURRENT_DATE AND (p.end_time IS NULL OR p.end_time >= CURRENT_TIME)))
          AND p.latitude IS NOT NULL
          AND p.longitude IS NOT NULL
    ";

    if ($exclude_user_id !== null) {
        $sql .= " AND p.id NOT IN (SELECT project_id FROM volunteer_applications WHERE user_id = :user_id)";
    }

    $sql .= " HAVING distance <= :radius
        ORDER BY distance ASC
        LIMIT 5";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':lat', $lat);
    $stmt->bindValue(':lng', $lng);
    $stmt->bindValue(':radius', $radius_km);
    if ($exclude_user_id !== null) {
        $stmt->bindValue(':user_id', $exclude_user_id, PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/**
 * Check if a project is past its date and time
 */
function project_is_past(array $project): bool
{
    $eventDate = $project['event_date'];
    $endTime = !empty($project['end_time']) ? $project['end_time'] : '23:59:59';
    return strtotime("$eventDate $endTime") < time();
}

/**
 * List completed projects for an NGO
 */
function project_list_completed_for_ngo(PDO $pdo, int $ngo_id)
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM projects
        WHERE ngo_id = ? 
          AND (status = 'closed' OR event_date < CURRENT_DATE)
        ORDER BY event_date DESC
    ");
    $stmt->execute([$ngo_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
