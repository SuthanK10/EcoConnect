<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=eco-connect', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop table if exists to recreate with correct types
    $pdo->exec("DROP TABLE IF EXISTS community_post_media");

    $sql = "CREATE TABLE community_post_media (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        media_path VARCHAR(255) NOT NULL,
        media_type ENUM('image', 'video') DEFAULT 'image',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (post_id)
    )";
    
    $pdo->exec($sql);
    echo "Table created successfully\n";
    
    // Migrating existing media
    // Note: Since I dropped the table, I don't need to check if it's already there
    $posts = $pdo->query("SELECT id, media_path, media_type FROM community_posts WHERE media_path IS NOT NULL AND media_path != ''")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($posts as $post) {
        $stmt = $pdo->prepare("INSERT INTO community_post_media (post_id, media_path, media_type) VALUES (?, ?, ?)");
        $stmt->execute([$post['id'], $post['media_path'], $post['media_type']]);
    }
    echo "Migrated existing media for " . count($posts) . " posts\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
