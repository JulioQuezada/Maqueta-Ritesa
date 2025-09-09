<?php
// cargar_productos.php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';

header('Content-Type: application/json; charset=utf-8');

$limit  = isset($_GET['limit'])  ? (int)$_GET['limit']  : 12;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$q      = isset($_GET['q'])      ? trim((string)$_GET['q']) : '';

try {
  if ($q !== '') {
    $items = searchProducts($pdo, $q, $limit);
    // en búsqueda no usamos offset simple; si quieres, añade paginación con OFFSET también
    $hasMore = count($items) === $limit; 
  } else {
    $items = getProducts($pdo, $limit, $offset, 'DESC');
    // Si recibes el total, aquí podrías calcular hasMore real.
    $hasMore = count($items) === max(1, min($limit, 50));
  }
  echo json_encode(['items' => $items, 'hasMore' => $hasMore], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  error_log('cargar_productos error: '.$e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Error interno']);
}
