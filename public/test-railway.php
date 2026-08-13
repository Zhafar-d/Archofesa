<?php
/**
 * Test Railway Environment
 * Akses: https://archofesa-production.up.railway.app/test-railway.php
 * 
 * File ini untuk test apakah Railway bisa menjalankan PHP
 */

header('Content-Type: application/json');

$info = [
    'status' => 'ok',
    'message' => 'Railway PHP is working!',
    'php_version' => phpversion(),
    'timestamp' => date('Y-m-d H:i:s'),
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
];

// Test .env file
$envPath = __DIR__ . '/../.env';
$info['env_file_exists'] = file_exists($envPath) ? 'yes' : 'no';

// Test Laravel bootstrap
$laravelPath = __DIR__ . '/../vendor/autoload.php';
$info['laravel_autoload_exists'] = file_exists($laravelPath) ? 'yes' : 'no';

// Test database connection using PDO directly
if (getenv('DB_HOST') || $_ENV['DB_HOST'] ?? false) {
    try {
        $host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'unknown');
        $port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '3306');
        $database = getenv('DB_DATABASE') ?: ($_ENV['DB_DATABASE'] ?? 'unknown');
        $username = getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? 'unknown');
        $password = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? '');
        
        $info['db_host'] = $host;
        $info['db_port'] = $port;
        $info['db_database'] = $database;
        $info['db_username'] = $username;
        
        $dsn = "mysql:host={$host};port={$port};dbname={$database}";
        $pdo = new PDO($dsn, $username, $password);
        $info['db_connection'] = 'success';
        
        // Test query
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM rooms');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $info['rooms_count'] = $result['count'];
        
    } catch (Exception $e) {
        $info['db_connection'] = 'failed';
        $info['db_error'] = $e->getMessage();
    }
} else {
    $info['db_connection'] = 'no_env_vars';
}

// Test Laravel app
if ($info['laravel_autoload_exists'] === 'yes') {
    try {
        require $laravelPath;
        $app = require __DIR__ . '/../bootstrap/app.php';
        $info['laravel_bootstrap'] = 'success';
        
    } catch (Exception $e) {
        $info['laravel_bootstrap'] = 'failed';
        $info['laravel_error'] = $e->getMessage();
    }
}

echo json_encode($info, JSON_PRETTY_PRINT);
