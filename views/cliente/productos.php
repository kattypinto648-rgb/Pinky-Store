<?php
$titulo = 'Productos';
require __DIR__ . '/../layout/headerClient.php';
?>

<section>
  <div class="productos-titulo">
    <h2><?= !empty($busqueda) ? 'Resultados: "' . htmlspecialchars($busqueda) . '"' : 'Productos' ?></h2>

    <form method="GET" action="/productos" class="buscador-minimal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
        <circle cx="11" cy="11" r="7"></circle>
        <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
      </svg>
      <input type="text" name="busqueda" placeholder="Buscar productos..." value="<?= htmlspecialchars($busqueda ?? '') ?>">
    </form>
  </div>

  <div class="productos-chips">
    <a href="/productos" class="chip <?= empty($_GET['cat']) ? 'chip-activo' : '' ?>">Todas</a>
    <a href="/productos?cat=Camisas"       class="chip <?= ($_GET['cat'] ?? '') === 'Camisas'       ? 'chip-activo' : '' ?>">Camisas</a>
    <a href="/productos?cat=Camperas"      class="chip <?= ($_GET['cat'] ?? '') === 'Camperas'      ? 'chip-activo' : '' ?>">Camperas</a>
    <a href="/productos?cat=Carteras"      class="chip <?= ($_GET['cat'] ?? '') === 'Carteras'      ? 'chip-activo' : '' ?>">Carteras</a>
    <a href="/productos?cat=Chombas"       class="chip <?= ($_GET['cat'] ?? '') === 'Chombas'       ? 'chip-activo' : '' ?>">Chombas</a>
    <a href="/productos?cat=Mangas%20largas" class="chip <?= ($_GET['cat'] ?? '') === 'Mangas largas' ? 'chip-activo' : '' ?>">Mangas largas</a>
    <a href="/productos?cat=Remeras"       class="chip <?= ($_GET['cat'] ?? '') === 'Remeras'       ? 'chip-activo' : '' ?>">Remeras</a>
    <a href="/productos?cat=Tops"          class="chip <?= ($_GET['cat'] ?? '') === 'Tops'          ? 'chip-activo' : '' ?>">Tops</a>
    <a href="/productos?cat=Buzos"         class="chip <?= ($_GET['cat'] ?? '') === 'Buzos'         ? 'chip-activo' : '' ?>">Buzos</a>
    <a href="/productos?cat=Pantalones"    class="chip <?= ($_GET['cat'] ?? '') === 'Pantalones'    ? 'chip-activo' : '' ?>">Pantalones</a>
    <a href="/productos?cat=Shorts"        class="chip <?= ($_GET['cat'] ?? '') === 'Shorts'        ? 'chip-activo' : '' ?>">Shorts</a>
    <a href="/productos?cat=Jeans"         class="chip <?= ($_GET['cat'] ?? '') === 'Jeans'         ? 'chip-activo' : '' ?>">Jeans</a>
  </div>

  <form method="GET" action="/productos" class="filtro-precio">
    <label>Desde $</label>
    <input type="number" name="min" placeholder="0" value="<?= htmlspecialchars($_GET['min'] ?? '') ?>" min="0">
    <label>Hasta $</label>
    <input type="number" name="max" placeholder="999999" value="<?= htmlspecialchars($_GET['max'] ?? '') ?>" min="0">
    <button type="submit">Filtrar</button>
    <a href="/productos" class="link-ver-todos">Limpiar</a>
  </form>

  <?php if (empty($productos)): ?>
    <p>No hay productos.</p>
  <?php else: ?>
    <div class="cards">
      <?php foreach ($productos as $p): ?>
        <div class="card">
          <div class="card-imagen-contenedor">
            <img src="<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
          </div>
          <h3><?= htmlspecialchars($p['nombre']) ?></h3>
          <span class="card-categoria"><?= htmlspecialchars($p['categoria']) ?></span>
          <span class="precio">$ <?= number_format($p['precio'], 0, ',', '.') ?></span>

          <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin'): ?>
            <p class="card-vista-admin">Vista de cliente</p>
          <?php else: ?>
            <select class="selector-talle" data-id="<?= $p['id'] ?>">
              <?php foreach ($p['variantes'] as $v): ?>
                <?php if ($v['stock'] > 0): ?>
                  <option value="<?= htmlspecialchars($v['talle']) ?>" data-stock="<?= (int)$v['stock'] ?>">
                    <?= htmlspecialchars($v['talle']) ?>
                  </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>

            <button type="button" class="boton-agregar"
                    data-id="<?= $p['id'] ?>"
                    data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                    data-precio="<?= $p['precio'] ?>"
                    onclick="agregarProducto(this)">
              Agregar
            </button>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<script>
function agregarProducto(boton) {
    const id = parseInt(boton.dataset.id);
    const nombre = boton.dataset.nombre;
    const precio = parseFloat(boton.dataset.precio);

    const select = boton.parentElement.querySelector('.selector-talle');
    const talle = select ? select.value : 'Unico';
    const opcion = select ? select.options[select.selectedIndex] : null;
    const stock = opcion ? parseInt(opcion.dataset.stock || '0') : 0;

    if (!stock || stock < 1) {
        mostrarAviso('Este talle no tiene stock');
        return;
    }

    let ok = true;

    if (window.pinkyCarrito && window.pinkyCarrito.agregarLocal) {
        ok = window.pinkyCarrito.agregarLocal(
            id, nombre, talle, precio, stock
        );
    } else {
        console.error('pinkyCarrito NO ESTA CARGADO');
    }

    if (!ok) {
        mostrarAviso('No hay más stock de ese talle');
        return;
    }

    <?php if (isset($_SESSION['usuario'])): ?>
    fetch('/carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + id + '&talle=' + encodeURIComponent(talle)
    })
    .then(r => r.json())
    .then(d => {
        if (!d.ok) {
            mostrarAviso('Error: ' + (d.msg || 'no se pudo agregar'));
        }
    })
    .catch(error => console.error('Error al agregar:', error));
    <?php endif; ?>

    boton.textContent = '✓ Agregado';
    boton.disabled = true;

    setTimeout(() => {
        boton.textContent = 'Agregar';
        boton.disabled = false;
    }, 1500);

    mostrarAviso('Producto agregado al carrito');
}


function mostrarAviso(texto) {
  let aviso = document.getElementById('aviso-carrito');
  if (!aviso) {
    aviso = document.createElement('div');
    aviso.id = 'aviso-carrito';
    aviso.className = 'aviso-carrito';
    document.body.appendChild(aviso);
  }
  aviso.textContent = texto;
  aviso.classList.add('visible');
  clearTimeout(window._avisoTimer);
  window._avisoTimer = setTimeout(() => {
    aviso.classList.remove('visible');
  }, 2500);
}
</script>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>