<?php
// all_products.php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
$productos = getProducts($pdo, 24, 0, 'DESC');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Todos los productos</title>
  <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
  <h1 class="h3 mb-3">Todos los productos</h1>
  <div class="row g-3">
    <?php foreach ($productos as $p): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100">
          <img src="<?= h($p['DirecImagen'] ?? '') ?>" class="card-img-top" alt="<?= h($p['Nombre'] ?? 'Producto') ?>" loading="lazy">
          <div class="card-body">
            <h2 class="h6 text-truncate" title="<?= h($p['Nombre'] ?? '') ?>"><?= h($p['Nombre'] ?? '') ?></h2>
            <p class="small text-muted mb-2"><?= h($p['Marca'] ?? '') ?> · <?= h($p['Categoría'] ?? '') ?></p>
            <a class="btn btn-sm btn-primary" href="details.php?id=<?= (int)$p['ID_producto'] ?>">Ver detalle</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
