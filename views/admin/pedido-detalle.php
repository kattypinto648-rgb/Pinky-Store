<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section>
  <div class="admin-seccion-titulo">
    <h2>Pedido <?= htmlspecialchars($pedido['codigo']) ?></h2>
    <a href="/admin/pedidos">Volver</a>
  </div>

  <div class="config-grid">
    <div class="config-card">
      <h3>Cliente</h3>
      <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['cliente_nombre'] . ' ' . $pedido['cliente_apellido']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($pedido['cliente_email']) ?></p>
      <p><strong>Fecha:</strong> <?= htmlspecialchars($pedido['fecha']) ?></p>
    </div>

    <div class="config-card">
      <h3>Pago y envío</h3>
      <p><strong>Método de pago:</strong> <?= htmlspecialchars($pedido['metodo_pago'] ?? '—') ?></p>
      <p><strong>Método de entrega:</strong> <?= htmlspecialchars($pedido['metodo_entrega'] ?? '—') ?></p>
      <p><strong>Estado:</strong> <?= htmlspecialchars($pedido['estado']) ?></p>
    </div>

    <div class="config-card">
      <h3>Totales</h3>
      <p><strong>Subtotal:</strong> $ <?= number_format($pedido['subtotal'], 0, ',', '.') ?></p>
      <p><strong>Envío:</strong> $ <?= number_format($pedido['costo_envio'], 0, ',', '.') ?></p>
      <p><strong>Total:</strong> $ <?= number_format($pedido['total'], 0, ',', '.') ?></p>
    </div>
  </div>

  <h2 style="margin-top:30px;">Productos</h2>

  <table>
    <tr>
      <th>Producto</th>
      <th>Talle</th>
      <th>Cantidad</th>
      <th>Precio unitario</th>
      <th>Subtotal</th>
    </tr>
    <?php foreach ($pedido['items'] as $it): ?>
      <tr>
        <td><?= htmlspecialchars($it['producto_nombre']) ?></td>
        <td><?= htmlspecialchars($it['talle']) ?></td>
        <td><?= $it['cantidad'] ?></td>
        <td>$ <?= number_format($it['precio_unit'], 0, ',', '.') ?></td>
        <td>$ <?= number_format($it['precio_unit'] * $it['cantidad'], 0, ',', '.') ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>