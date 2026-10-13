<?php require __DIR__ . '/../layout/headerClient.php'; ?>

<section class="banner">
  <img src="/img/banner/banner1.jpg" alt="Banner 1" class="activa">
  <img src="/img/banner/banner2.jpeg" alt="Banner 2">
  <img src="/img/banner/banner3.jpeg" alt="Banner 3">
</section>

<div class="seccion-beneficios">
  <div class="beneficio-item">
    <svg class="beneficio-icono" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2">
      <path d="M1 3h15v13H1zM16 8h4l3 3v5h-7z"/>
      <circle cx="5.5" cy="18.5" r="2.5"/>
      <circle cx="18.5" cy="18.5" r="2.5"/>
    </svg>
    <h4>Envío a todo el país</h4>
  </div>

  <div class="beneficio-item">
    <svg class="beneficio-icono" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2">
      <rect x="1" y="4" width="22" height="16" rx="2"/>
      <line x1="1" y1="10" x2="23" y2="10"/>
    </svg>
    <h4>3 cuotas sin interés</h4>
  </div>

  <div class="beneficio-item">
    <svg class="beneficio-icono" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2">
      <path d="M3 12a9 9 0 1 0 3-6.7"/>
      <polyline points="3 4 3 9 8 9"/>
    </svg>
    <h4>Cambios fáciles</h4>
    <p>hasta 30 días</p>
  </div>

  <div class="beneficio-item">
    <svg class="beneficio-icono" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2">
      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <h4>Atención personalizada</h4>
  </div>
</div>

<section>
  <div class="seccion-titulo-link">
    <h2>Destacados</h2>
    <a href="/productos" class="link-ver-todos">Ver todos</a>
  </div>

  <div class="cards">
    <?php foreach ($productos as $p): ?>
      <div class="card">
        <div class="card-imagen-contenedor">
          <img src="<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
        </div>
        <h3><?= htmlspecialchars($p['nombre']) ?></h3>
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
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>