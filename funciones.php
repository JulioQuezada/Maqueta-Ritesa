<?php
// funciones.php
declare(strict_types=1);
require_once __DIR__ . '/conexion.php';
session_start();

// Configura el nombre real de la tabla:
const TABLA_PRODUCTOS = 'ritesa_productos_2023_08_18';

function h(?string $v): string {
  return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string {
  if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf'];
}

function csrf_check(string $token): bool {
  return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

function getProducts(PDO $pdo, int $limit = 12, int $offset = 0, ?string $order = 'DESC'): array {
  $limit  = max(1, min($limit, 50));
  $offset = max(0, $offset);
  $order  = ($order === 'ASC') ? 'ASC' : 'DESC';
  $sql = "SELECT ID_producto, Nombre, Descripcion, Marca, `Categoría`, SKU, DirecImagen
          FROM ".TABLA_PRODUCTOS."
          ORDER BY ID_producto {$order}
          LIMIT :limit OFFSET :offset";
  $st = $pdo->prepare($sql);
  $st->bindValue(':limit',  $limit,  PDO::PARAM_INT);
  $st->bindValue(':offset', $offset, PDO::PARAM_INT);
  $st->execute();
  return $st->fetchAll();
}

function searchProducts(PDO $pdo, string $q, int $limit = 50): array {
  $limit = max(1, min($limit, 50));
  $q = trim($q);
  if ($q === '') return [];
  $like = "%{$q}%";
  $sql = "SELECT ID_producto, Nombre, Descripcion, Marca, `Categoría`, SKU, DirecImagen
          FROM ".TABLA_PRODUCTOS."
          WHERE Nombre LIKE :q OR Descripcion LIKE :q OR Marca LIKE :q OR `Categoría` LIKE :q OR SKU LIKE :q
          LIMIT :limit";
  $st = $pdo->prepare($sql);
  $st->bindValue(':q', $like, PDO::PARAM_STR);
  $st->bindValue(':limit', $limit, PDO::PARAM_INT);
  $st->execute();
  return $st->fetchAll();
}

function getProductById(PDO $pdo, int $id): ?array {
  $st = $pdo->prepare("SELECT ID_producto, Nombre, Descripcion, Marca, `Categoría`, SKU, DirecImagen
                       FROM ".TABLA_PRODUCTOS." WHERE ID_producto = :id");
  $st->bindValue(':id', $id, PDO::PARAM_INT);
  $st->execute();
  $row = $st->fetch();
  return $row ?: null;
}

function getByCategory(PDO $pdo, string $cat, int $limit = 100): array {
  $limit = max(1, min($limit, 100));
  $like = "%".trim($cat)."%";
  $st = $pdo->prepare("SELECT ID_producto, Nombre, Descripcion, Marca, `Categoría`, SKU, DirecImagen
                       FROM ".TABLA_PRODUCTOS." WHERE `Categoría` LIKE :cat LIMIT :lim");
  $st->bindValue(':cat', $like, PDO::PARAM_STR);
  $st->bindValue(':lim', $limit, PDO::PARAM_INT);
  $st->execute();
  return $st->fetchAll();
}
