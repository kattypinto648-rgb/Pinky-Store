<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<?php
$variantesActuales = $producto['variantes'] ?? [];
$stockPorTalle = [];
foreach ($variantesActuales as $v) {
    $stockPorTalle[$v['talle']] = (int)$v['stock'];
}
$tallesPosibles = ['XS', 'S', 'M', 'L', 'XL', 'Unico'];
?>

<section class="centrado">
  <h2><?= $producto ? 'Editar producto' : 'Nuevo producto' ?></h2>

  <form method="POST" action="/admin/productos/guardar" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $producto['id'] ?? '' ?>">

    <label for="codigo">Código</label>
    <input type="text" id="codigo" name="codigo"
           value="<?= htmlspecialchars($producto['codigo'] ?? '') ?>"
           placeholder="CAM-01" required>

    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre"
           value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>"
           placeholder="Camisa Oxford Blanca" required>

    <label for="descripcion">Descripción</label>
    <input type="text" id="descripcion" name="descripcion"
           value="<?= htmlspecialchars($producto['descripcion'] ?? '') ?>">

    <label for="categoria">Categoría</label>
    <input type="text" id="categoria" name="categoria"
           value="<?= htmlspecialchars($producto['categoria'] ?? '') ?>"
           placeholder="Camisas" required>

    <label for="color">Color</label>
    <input type="text" id="color" name="color"
           value="<?= htmlspecialchars($producto['color'] ?? '') ?>"
           placeholder="Blanco">

    <label for="precio">Precio</label>
    <input type="number" id="precio" name="precio" step="0.01" min="1"
           value="<?= htmlspecialchars($producto['precio'] ?? '') ?>"
           placeholder="100000" required>

    <label>Imagen actual</label>
    <?php if (!empty($producto['imagen'])): ?>
      <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt=""
           style="width:100px;height:auto;display:block;margin-bottom:8px;">
    <?php endif; ?>

    <label for="imagen">Subir nueva imagen (opcional)</label>
    <input type="file" id="imagen" name="imagen" accept="image/*">

    <label for="imagen_url">O pegar URL de imagen</label>
    <input type="text" id="imagen_url" name="imagen_url"
           value="<?= htmlspecialchars($producto['imagen'] ?? '') ?>"
           placeholder="/img/productos/...">

    <fieldset class="talles-fieldset">
      <legend>Talles y stock</legend>

      <table class="talles-tabla">
        <?php foreach ($tallesPosibles as $t): ?>
          <?php
            $tieneTalle = isset($stockPorTalle[$t]);
            $stockVal = $stockPorTalle[$t] ?? 0;
          ?>
          <tr>
            <td style="width:40px;">
              <input type="checkbox" name="talles[]" value="<?= $t ?>" id="talle_<?= $t ?>"
                     <?= $tieneTalle ? 'checked' : '' ?>
                     onchange="toggleStock('<?= $t ?>')">
            </td>
            <td>
              <label for="talle_<?= $t ?>"><?= $t ?></label>
            </td>
            <td style="width:100px;text-align:right;">
              <input type="number" name="stock_<?= $t ?>" id="stock_<?= $t ?>"
                     min="0" value="<?= $stockVal ?>"
                     <?= $tieneTalle ? '' : 'disabled' ?>>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </fieldset>

    <button type="submit">Guardar</button>
  </form>

  <p><a href="/admin/productos">Volver</a></p>
</section>

<script>
function toggleStock(talle) {
  const chk = document.getElementById('talle_' + talle);
  const inp = document.getElementById('stock_' + talle);
  if (!chk || !inp) return;
  inp.disabled = !chk.checked;
  if (chk.checked && inp.value === '0') inp.value = '1';
}
</script>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>