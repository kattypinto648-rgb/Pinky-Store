<?php require __DIR__ . '/../layout/headerClient.php'; ?>

<section>
  <h2>Mi carrito</h2>

  <?php if (empty($items)): ?>
    <div class="carrito-vacio">
      <p>Tu carrito está vacío.</p>
      <a href="/productos" class="boton-agregar" style="display:inline-block;text-decoration:none;padding:10px 20px;">Ir a productos</a>
    </div>
  <?php else: ?>
    <table>
      <tr>
        <th>Producto</th>
        <th>Talle</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Subtotal</th>
        <th></th>
      </tr>
      <?php foreach ($items as $i => $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['nombre']) ?></td>
          <td><?= htmlspecialchars($item['talle']) ?></td>
          <td>$ <?= number_format($item['precio'], 0, ',', '.') ?></td>
          <td>
            <form method="POST" action="/carrito/cantidad" style="display:inline">
              <input type="hidden" name="indice" value="<?= $i ?>">
              <input type="hidden" name="delta" value="-1">
              <button type="submit" class="btn-cantidad">-</button>
            </form>
            <span class="cantidad-valor"><?= $item['cantidad'] ?></span>
            <form method="POST" action="/carrito/cantidad" style="display:inline">
              <input type="hidden" name="indice" value="<?= $i ?>">
              <input type="hidden" name="delta" value="1">
              <button type="submit" class="btn-cantidad">+</button>
            </form>
          </td>
          <td>$ <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
          <td>
            <form method="POST" action="/carrito/eliminar" style="display:inline">
              <input type="hidden" name="indice" value="<?= $i ?>">
              <button type="submit" class="btn-eliminar">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <div class="carrito-total">
      <span>Total:</span>
      <strong>$ <?= number_format($total, 0, ',', '.') ?></strong>
    </div>

    <div class="carrito-acciones">
      <a href="/productos" class="link-ver-todos">← Seguir comprando</a>
      <a href="/checkout" class="boton-agregar" style="display:inline-block;text-decoration:none;padding:10px 24px;width:auto;">Finalizar compra</a>
    </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>