<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section class="centrado">
  <h2><?= $promo ? 'Editar promocion' : 'Nueva promocion' ?></h2>

  <form method="POST" action="/admin/promociones/guardar">
    <input type="hidden" name="id" value="<?= $promo['id'] ?? '' ?>">

    <label for="codigo">Codigo</label>
    <input type="text" id="codigo" name="codigo"
           value="<?= htmlspecialchars($promo['codigo'] ?? '') ?>"
           placeholder="PROMO01" required>

    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre"
           value="<?= htmlspecialchars($promo['nombre'] ?? '') ?>"
           placeholder="Descuento verano" required>

    <label for="porcentaje">Porcentaje (1-100)</label>
    <input type="number" id="porcentaje" name="porcentaje"
           min="1" max="100"
           value="<?= htmlspecialchars($promo['porcentaje'] ?? '') ?>"
           placeholder="20" required>

    <label>
      <input type="checkbox" name="activa" value="1"
             <?= (!isset($promo) || $promo['activa']) ? 'checked' : '' ?>>
      Activa
    </label>

    <button type="submit">Guardar</button>
  </form>

  <p><a href="/admin/promociones">Volver</a></p>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>