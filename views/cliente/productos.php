<?php require __DIR__ . '/../layout/headerClient.php'; ?>

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
    <a href="/productos?cat=Mangas+largas" class="chip <?= ($_GET['cat'] ?? '') === 'Mangas largas' ? 'chip-activo' : '' ?>">Mangas largas</a>
    <a href="/productos?cat=Remeras"       class="chip <?= ($_GET['cat'] ?? '') === 'Remeras'       ? 'chip-activo' : '' ?>">Remeras</a>
  </div>

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

          <form method="POST" action="/carrito/agregar">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <select name="talle" required>
              <?php foreach ($p['variantes'] as $v): ?>
                <?php if ($v['stock'] > 0): ?>
                  <option value="<?= htmlspecialchars($v['talle']) ?>"><?= htmlspecialchars($v['talle']) ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="boton-agregar">Agregar</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>