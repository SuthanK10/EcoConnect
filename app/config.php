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
$app_env = $_ENV['APP_ENV'] ?? 'production';
$is_production = ($app_env === 'production');

// Database Configuration
if ($is_production) {
    // Production Credentials from .env
    $host = $_ENV['PROD_DB_HOST'] ?? 'sql300.infinityfree.com';
    $user = $_ENV['PROD_DB_USER'] ?? '';
    $pass = $_ENV['PROD_DB_PASS'] ?? '';
    $db   = $_ENV['PROD_DB_NAME'] ?? '';
} else {
    // Localhost Development Credentials
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db   = $_ENV['DB_NAME'] ?? 'eco-connect';
    $user = $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? '';
}

// Define Base URL for assets
if ($is_production) {
    // Replace with your InfinityFree URL if it differs
    $raw_base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    // If the script is in /public, remove it from the base URL to treat the parent as root
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
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET time_zone = '+05:30'");
} catch (PDOException $e) {
    echo 'Database connection failed: ' . htmlspecialchars($e->getMessage());
    exit;
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
