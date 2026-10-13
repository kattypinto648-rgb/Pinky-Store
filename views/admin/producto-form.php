<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section class="centrado">
  <h2><?= $producto ? 'Editar producto' : 'Nuevo producto' ?></h2>

  <form method="POST" action="/admin/productos/guardar">
    <input type="hidden" name="id" value="<?= $producto['id'] ?? '' ?>">

    <label for="codigo">Codigo</label>
    <input type="text" id="codigo" name="codigo"
           value="<?= htmlspecialchars($producto['codigo'] ?? '') ?>" required>

    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre"
           value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>" required>

    <label for="descripcion">Descripcion</label>
    <input type="text" id="descripcion" name="descripcion"
           value="<?= htmlspecialchars($producto['descripcion'] ?? '') ?>">

    <label for="categoria">Categoria</label>
    <input type="text" id="categoria" name="categoria"
           value="<?= htmlspecialchars($producto['categoria'] ?? '') ?>" required>

    <label for="color">Color</label>
    <input type="text" id="color" name="color"
           value="<?= htmlspecialchars($producto['color'] ?? '') ?>">

    <label for="precio">Precio</label>
    <input type="number" id="precio" name="precio" step="0.01" min="1"
           value="<?= htmlspecialchars($producto['precio'] ?? '') ?>" required>

    <label for="imagen">URL de la imagen</label>
    <input type="text" id="imagen" name="imagen"
           value="<?= htmlspecialchars($producto['imagen'] ?? '') ?>" required>

    <button type="submit">Guardar</button>
  </form>

  <p><a href="/admin/productos">Volver</a></p>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>