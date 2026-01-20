<?php
// db_fix.php
require_once 'app/config.php';

echo "<h1>Database Connection Fixer</h1>";

$tests = [
    '127.0.0.1' => ['host' => '127.0.0.1', 'user' => 'root', 'pass' => ''],
    'localhost' => ['host' => 'localhost', 'user' => 'root', 'pass' => ''],
    '127.0.0.1:3306' => ['host' => '127.0.0.1;port=3306', 'user' => 'root', 'pass' => ''],
    'localhost:3306' => ['host' => 'localhost;port=3306', 'user' => 'root', 'pass' => ''],
];

$db_name = 'eco-connect';

foreach ($tests as $label => $config) {
    echo "<h3>Testing $label...</h3>";
    try {
        $dsn = "mysql:host={$config['host']};dbname=$db_name;charset=utf8mb4";
        $test_pdo = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2
        ]);
        echo "<b style='color:green'>SUCCESS!</b> Connection worked using $label.<br>";
        echo "<i>Recommendation: Set DB_HOST=$label in your .env file.</i><hr>";
    } catch (PDOException $e) {
        echo "<span style='color:red'>FAILED:</span> " . htmlspecialchars($e->getMessage()) . "<hr>";
    }
}

echo "<h2>System Info</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Name: " . $_SERVER['SERVER_NAME'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "App Env: " . (isset($app_env) ? $app_env : 'Not Defined') . "<br>";
?>
