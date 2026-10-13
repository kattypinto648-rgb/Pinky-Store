<?php require __DIR__ . '/../layout/headerClient.php'; ?>

<section>
  <h2>Mis pedidos</h2>

  <?php if ($ok): ?>
    <p class="ok">Compra confirmada. Código: <?= htmlspecialchars($ok) ?></p>
  <?php endif; ?>

  <?php if (isset($_GET['cancelado'])): ?>
    <p class="ok">Pedido cancelado correctamente</p>
  <?php endif; ?>

  <?php if (empty($pedidos)): ?>
    <p>Todavía no tenés pedidos
      
    </p>
    <p><a href="/productos">Ver productos</a></p>
  <?php else: ?>
    <table>
      <tr>
        <th>Código</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Estado</th>
        <th>Detalle</th>
        <th>Acciones</th>
      </tr>
      <?php foreach ($pedidos as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['codigo']) ?></td>
          <td><?= htmlspecialchars($p['fecha']) ?></td>
          <td>$ <?= number_format($p['total'], 0, ',', '.') ?></td>
          <td>
            <span class="admin-estado estado-<?= strtolower($p['estado']) ?>">
              <?= htmlspecialchars($p['estado']) ?>
            </span>
          </td>
          <td>
            <?php foreach ($p['items'] as $it): ?>
              <div class="pedido-item">
                <?= htmlspecialchars($it['producto_nombre']) ?>
                — Talle <?= htmlspecialchars($it['talle']) ?>
                × <?= $it['cantidad'] ?>
                = $ <?= number_format($it['precio_unit'] * $it['cantidad'], 0, ',', '.') ?>
              </div>
            <?php endforeach; ?>
            <div class="pedido-item pedido-item-total">
              <strong>Envío:</strong> <?= htmlspecialchars($p['metodo_entrega']) ?>
              ($ <?= number_format($p['costo_envio'], 0, ',', '.') ?>)
            </div>
            <div class="pedido-item">
              <strong>Pago:</strong> <?= htmlspecialchars($p['metodo_pago']) ?>
            </div>
          </td>
          <td>
            <?php if ($p['estado'] === 'Pendiente'): ?>
              <form method="POST" action="/mis-pedidos/cancelar"
                    onsubmit="return confirm('¿Seguro que querés cancelar este pedido?')">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn-eliminar">Cancelar</button>
              </form>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>