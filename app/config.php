<?php
// app/config.php
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env file
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

date_default_timezone_set('Asia/Colombo');

// Auto-detect environment
$is_localhost_url = (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1'));
$app_env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? ($is_localhost_url ? 'local' : 'production');
$is_production = ($app_env === 'production');

// Database Configuration
if ($is_production) {
    // Production Credentials
    $host = trim($_ENV['PROD_DB_HOST'] ?: $_SERVER['PROD_DB_HOST'] ?: 'sql300.infinityfree.com');
    $user = trim($_ENV['PROD_DB_USER'] ?: $_SERVER['PROD_DB_USER'] ?: 'if0_40760324');
    $pass = trim($_ENV['PROD_DB_PASS'] ?: $_SERVER['PROD_DB_PASS'] ?: 'audiveron108716');
    $db   = trim($_ENV['PROD_DB_NAME'] ?: $_SERVER['PROD_DB_NAME'] ?: 'if0_40760324_ecoconnect');
} else {
    // Localhost Development Credentials (XAMPP usually works best with localhost or 127.0.0.1)
    $host = trim($_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? 'localhost');
    $db   = trim($_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? 'eco-connect');
    $user = trim($_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? 'root');
    $pass = trim($_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? '');
}

// Define Base URL for assets
if ($is_production) {
    $raw_base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $base_url = preg_replace('/\/public$/', '', $raw_base);
} else {
    $base_url = 'http://localhost/Eco-Connect';
}
define('BASE_URL', rtrim($base_url, '/'));

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET time_zone = '+05:30'");
} catch (PDOException $e) {
    // If localhost fails with 2002, try 127.0.0.1 as a fallback automatically
    if ($host === 'localhost' && !$is_production) {
        try {
            $dsn_fallback = "mysql:host=127.0.0.1;dbname=$db;charset=$charset";
            $pdo = new PDO($dsn_fallback, $user, $pass, $options);
            $pdo->exec("SET time_zone = '+05:30'");
        } catch (PDOException $e2) {
            echo 'Database connection failed: ' . htmlspecialchars($e2->getMessage());
            exit;
        }
    } else {
        echo 'Database connection failed: ' . htmlspecialchars($e->getMessage());
        exit;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Update user activity
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE users SET last_active_at = NOW() WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Stripe Configuration
define('STRIPE_PUBLISHABLE_KEY', $_ENV['STRIPE_PUBLISHABLE_KEY'] ?? '');
define('STRIPE_SECRET_KEY', $_ENV['STRIPE_SECRET_KEY'] ?? '');
