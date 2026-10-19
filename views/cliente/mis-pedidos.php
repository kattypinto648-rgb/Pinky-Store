<?php
$titulo = 'Mis pedidos';
require __DIR__ . '/../layout/headerClient.php';
?>

<section>
  <h2>Mis pedidos</h2>

  <?php if ($ok): ?>
  <p class="ok">Compra confirmada. Código: <?= htmlspecialchars($ok) ?></p>
  <p class="aclaracion">Te enviamos un email con los datos del pedido a <strong><?= htmlspecialchars($_SESSION['usuario']['email']) ?></strong>.</p>
<?php endif; ?>

  <?php if (empty($pedidos)): ?>
    <p>Todavía no tenés pedidos.</p>
    <p><a href="/productos">Ver productos</a></p>
  <?php else: ?>
    <table>
      <tr>
        <th>Código</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Estado</th>
        <th>Detalle</th>
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
                <?= htmlspecialchars($it['producto_nombre']) ?> — Talle <?= htmlspecialchars($it['talle']) ?> × <?= $it['cantidad'] ?>
              </div>
            <?php endforeach; ?>

            <?php
              require_once __DIR__ . '/../../src/models/Pedido.php';
              $historial = \Pedido::historial((int)$p['id']);
            ?>
            <?php if (!empty($historial)): ?>
              <details style="margin-top:8px;font-size:12px;">
                <summary style="cursor:pointer;color:#b56b83;">Ver historial de estados</summary>
                <ul style="margin:6px 0;padding-left:16px;">
                  <?php foreach ($historial as $h): ?>
                    <li><?= htmlspecialchars($h['fecha']) ?> — <?= htmlspecialchars($h['estado']) ?> (<?= htmlspecialchars($h['usuario_nombre'] ?? 'sistema') ?>)</li>
                  <?php endforeach; ?>
                </ul>
              </details>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>