<?php
// conexion.php
declare(strict_types=1);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

require_once __DIR__ . '/vendor/autoload.php'; // opcional si usas Dotenv/PHPMailer

// Carga .env si existe (recomendado)
if (file_exists(__DIR__.'/.env')) {
  Dotenv\Dotenv::createImmutable(__DIR__)->load();
}

$DB_HOST = $_ENV['DB_HOST'] ?? '127.0.0.1';
$DB_NAME = $_ENV['DB_NAME'] ?? 'basedatos';
$DB_USER = $_ENV['DB_USER'] ?? 'usuario';
$DB_PASS = $_ENV['DB_PASS'] ?? 'clave';

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
  error_log('DB connection error: '.$e->getMessage());
  http_response_code(500);
  exit('Error interno.'); // mensaje genérico
}
