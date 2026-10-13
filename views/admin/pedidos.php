<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section>
  <h2>Pedidos</h2>

  <?php if (empty($pedidos)): ?>
    <p>No hay pedidos cargados.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Código</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
      <?php foreach ($pedidos as $p): ?>
        <tr>
          <td>
            <a href="/admin/pedidos/ver/<?= $p['id'] ?>" style="color:#b56b83;text-decoration:none;font-weight:bold;">
              <?= htmlspecialchars($p['codigo']) ?>
            </a>
          </td>
          <td><?= htmlspecialchars($p['cliente_nombre'] . ' ' . $p['cliente_apellido']) ?></td>
          <td><?= htmlspecialchars($p['fecha']) ?></td>
          <td>$ <?= number_format($p['total'], 0, ',', '.') ?></td>
          <td>
            <span class="admin-estado estado-<?= strtolower($p['estado']) ?>">
              <?= htmlspecialchars($p['estado']) ?>
            </span>
          </td>
          <td class="tabla-acciones">
            <form method="POST" action="/admin/pedidos/estado" style="display:inline">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <select name="estado">
                <option value="Pendiente" <?= $p['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="Enviado"   <?= $p['estado'] === 'Enviado'   ? 'selected' : '' ?>>Enviado</option>
                <option value="Entregado" <?= $p['estado'] === 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                <option value="Cancelado" <?= $p['estado'] === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
              </select>
              <button type="submit">Actualizar</button>
            </form>

            <form method="POST" action="/admin/pedidos/eliminar" style="display:inline"
                  onsubmit="return confirm('¿Eliminar este pedido?')">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>