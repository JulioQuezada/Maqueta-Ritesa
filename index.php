<?php
// index.php
declare(strict_types=1);
require_once __DIR__ . '/funciones.php';
$token = csrf_token();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Productos | Catálogo</title>
  <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="container py-3">
  <h1 class="h3">Catálogo de Productos</h1>
  <form id="form-buscar" class="row g-2 mt-2">
    <div class="col-sm-8 col-md-6">
      <input class="form-control" type="search" name="q" placeholder="Buscar por nombre, marca, categoría o SKU">
      <input type="hidden" name="csrf" value="<?php echo h($token); ?>">
    </div>
    <div class="col-auto">
      <button class="btn btn-primary" type="submit">Buscar</button>
    </div>
  </form>
</header>

<main class="container my-4">
  <div id="grid" class="row g-3"></div>
  <div class="d-flex justify-content-center my-3">
    <button id="btn-mas" class="btn btn-outline-secondary">Cargar más</button>
  </div>
</main>

<script defer src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script defer src="main.js"></script>
<script defer>
(() => {
  const grid   = document.getElementById('grid');
  const btnMas = document.getElementById('btn-mas');
  const form   = document.getElementById('form-buscar');

  let offset = 0, limit = 12, q = '';

  function card(p) {
    const img = p.DirecImagen ? p.DirecImagen : 'assets/img/placeholder.png';
    return `
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100">
          <img src="${img}" class="card-img-top" alt="${p.Nombre ? p.Nombre.replace(/"/g,'&quot;') : 'Producto'}" loading="lazy">
          <div class="card-body">
            <h2 class="h6 card-title text-truncate" title="${p.Nombre || ''}">${p.Nombre || ''}</h2>
            <p class="small text-muted mb-2">${p.Marca || ''} · ${p['Categoría'] || ''}</p>
            <a class="btn btn-sm btn-primary" href="details.php?id=${encodeURIComponent(p.ID_producto)}">Ver detalle</a>
          </div>
        </div>
      </div>`;
  }

  async function cargar(reset=false) {
    const params = new URLSearchParams({ limit, offset, q });
    const res = await fetch('cargar_productos.php?'+params.toString(), { headers: { 'Accept': 'application/json' }});
    if (!res.ok) return;
    const data = await res.json();
    if (reset) grid.innerHTML = '';
    data.items.forEach(p => { grid.insertAdjacentHTML('beforeend', card(p)); });
    offset += data.items.length;
    if (!data.hasMore) btnMas.disabled = true;
  }

  btnMas.addEventListener('click', () => cargar());

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    q = (fd.get('q') || '').trim();
    offset = 0;
    btnMas.disabled = false;
    cargar(true);
  });

  // primera carga
  cargar();
})();
</script>
</body>
</html>
