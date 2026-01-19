<?php
// app/models/Partner.php

function partner_all(PDO $pdo) {
    $stmt = $pdo->query("SELECT * FROM partners ORDER BY name ASC");
    return $stmt->fetchAll();
}


function partner_find_by_id(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM partners WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
