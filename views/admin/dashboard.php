<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section class="admin-bienvenida">
  <h1>Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></h1>
  <p>Esto es lo que pasó en tu tienda hoy</p>
</section>

<section class="admin-tarjetas">
  <div class="admin-tarjeta">
    <svg class="admin-tarjeta-icono" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M3 9l9-6 9 6v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
    </svg>
    <div>
      <span class="admin-tarjeta-label">Productos</span>
      <span class="admin-numero"><?= count($productos) ?></span>
    </div>
  </div>

  <div class="admin-tarjeta">
    <svg class="admin-tarjeta-icono" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M6 2h12l1 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6z"/>
      <path d="M5 6h14M9 10h6"/>
    </svg>
    <div>
      <span class="admin-tarjeta-label">Pedidos</span>
      <span class="admin-numero"><?= count($pedidos) ?></span>
    </div>
  </div>

  <div class="admin-tarjeta">
    <svg class="admin-tarjeta-icono" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="8" r="4"/>
      <path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/>
    </svg>
    <div>
      <span class="admin-tarjeta-label">Clientes</span>
      <span class="admin-numero"><?= $totalClientes ?></span>
    </div>
  </div>

  <div class="admin-tarjeta">
    <svg class="admin-tarjeta-icono" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M12 2v20M17 6H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
    </svg>
    <div>
      <span class="admin-tarjeta-label">Recaudado</span>
      <span class="admin-numero">$ <?= number_format($totalRecaudado, 0, ',', '.') ?></span>
    </div>
  </div>
</section>

<section>
  <div class="admin-seccion-titulo">
    <h2>Pedidos recientes</h2>
    <a href="/admin/pedidos">Ver todos</a>
  </div>

  <?php if (empty($pedidosRecientes)): ?>
    <p>No hay recientes</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Codigo</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Estado</th>
      </tr>
      <?php foreach ($pedidosRecientes as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['codigo']) ?></td>
          <td><?= htmlspecialchars($p['cliente_nombre'] . ' ' . $p['cliente_apellido']) ?></td>
          <td><?= htmlspecialchars($p['fecha']) ?></td>
          <td>$ <?= number_format($p['total'], 0, ',', '.') ?></td>
          <td>
            <span class="admin-estado estado-<?= strtolower($p['estado']) ?>">
              <?= htmlspecialchars($p['estado']) ?>
            </span>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</section>

<section>
  <div class="admin-seccion-titulo">
    <h2>Productos destacados</h2>
    <a href="/admin/productos">Ver todos</a>
  </div>

  <div class="cards">
    <?php foreach ($productosDestacados as $p): ?>
      <div class="card">
        <div class="card-imagen-contenedor">
          <img src="<?= htmlspecialchars($p['imagen']) ?>" alt="">
        </div>
        <h3><?= htmlspecialchars($p['nombre']) ?></h3>
        <span class="precio">$ <?= number_format($p['precio'], 0, ',', '.') ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>