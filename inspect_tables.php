<?php
require_once __DIR__ . '/app/config.php';

echo "NGOs TABLE SCHEMA:\n";
$s = $pdo->query("DESCRIBE ngos");
foreach($s->fetchAll(PDO::FETCH_ASSOC) as $c) {
    echo "- {$c['Field']} ({$c['Type']})\n";
}

echo "\nPARTNERS TABLE SCHEMA:\n";
try {
    $s = $pdo->query("DESCRIBE partners");
    foreach($s->fetchAll(PDO::FETCH_ASSOC) as $c) {
        echo "- {$c['Field']} ({$c['Type']})\n";
    }
} catch (Exception $e) {
    echo "Error describing partners: " . $e->getMessage() . "\n";
}

echo "\nPURPOSE CHECK:\n";
$ngoCount = $pdo->query("SELECT COUNT(*) FROM ngos")->fetchColumn();
echo "Total NGOs: $ngoCount\n";

try {
    $partnerCount = $pdo->query("SELECT COUNT(*) FROM partners")->fetchColumn();
    echo "Total Partners: $partnerCount\n";
} catch (Exception $e) {
    echo "Could not count partners: " . $e->getMessage() . "\n";
}
