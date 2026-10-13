<?php require __DIR__ . '/../layout/headerClient.php'; ?>

<section class="centrado">
  <h2>Finalizar compra</h2>

  <?php if (($_GET['error'] ?? null) === 'datos'): ?>
    <p class="error">Elegí método de pago y entrega.</p>
  <?php endif; ?>

  <form method="POST" action="/checkout">
    <label for="metodo_pago">Método de pago</label>
    <select id="metodo_pago" name="metodo_pago" required>
      <option value="">-- Elegir --</option>
      <option value="Tarjeta de crédito">Tarjeta de crédito</option>
      <option value="Transferencia">Transferencia</option>
      <option value="Efectivo">Efectivo</option>
    </select>

    <label for="metodo_entrega">Método de entrega</label>
    <select id="metodo_entrega" name="metodo_entrega" required>
      <option value="">-- Elegir --</option>
      <option value="Envío a domicilio">Envío a domicilio (+$2.500)</option>
      <option value="Retiro en local">Retiro en local (sin cargo)</option>
    </select>

    <p class="precio">Total productos: $ <?= number_format($total, 0, ',', '.') ?></p>
    <p class="aclaracion">El costo de envío se suma al confirmar.</p>

    <button type="submit">Confirmar compra</button>
  </form>

  <p><a href="/carrito">Volver al carrito</a></p>
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>