<?php
// funciones_categorias.php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
header('Content-Type: application/json; charset=utf-8');

$cat = trim((string)($_GET['categoria'] ?? ''));
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

if ($cat === '') {
  echo json_encode(['items' => []]); exit;
}

try {
  $items = getByCategory($pdo, $cat, $limit);
  echo json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  error_log('categorias error: '.$e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Error interno']);
}
