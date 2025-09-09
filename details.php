<?php
// details.php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit('ID inválida'); }
$producto = getProductById($pdo, $id);
if (!$producto) { http_response_code(404); exit('Producto no encontrado'); }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= h($producto['Nombre'] ?? 'Detalle') ?></title>
  <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendor/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="container py-4">
  <div class="row g-4">
    <div class="col-lg-7">
      <div class="swiper products-details-slider">
        <div class="swiper-wrapper">
          <?php
            $img = $producto['DirecImagen'] ?? '';
            $imgs = $img ? [$img] : ['assets/img/placeholder.png'];
            foreach ($imgs as $src):
          ?>
          <div class="swiper-slide">
            <img src="<?= h($src) ?>" alt="<?= h($producto['Nombre'] ?? 'Producto') ?>" loading="lazy" class="img-fluid">
          </div>
          <?php endforeach; ?>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
    <div class="col-lg-5">
      <h1 class="h4 mb-2"><?= h($producto['Nombre'] ?? '') ?></h1>
      <p class="text-muted mb-2"><?= h($producto['Marca'] ?? '') ?> · <?= h($producto['Categoría'] ?? '') ?></p>
      <p><?= nl2br(h($producto['Descripcion'] ?? '')) ?></p>
      <?php if (!empty($producto['SKU'])): ?>
        <p><strong>SKU:</strong> <?= h($producto['SKU']) ?></p>
      <?php endif; ?>
      <a class="btn btn-secondary mt-2" href="index.php">Volver</a>
    </div>
  </div>
</main>

<script defer src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script defer src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script defer src="main.js"></script>
</body>
</html>
