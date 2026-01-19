<?php
$pdo = new PDO('mysql:host=localhost;dbname=EcoConnect', 'root', '');
$res = $pdo->query("DESCRIBE community_posts")->fetchAll(PDO::FETCH_ASSOC);
print_r($res);
