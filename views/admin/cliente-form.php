<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section class="centrado">
  <h2><?= $cliente ? 'Editar cliente' : 'Nuevo cliente' ?></h2>

  <form method="POST" action="/admin/clientes/guardar">
    <input type="hidden" name="id" value="<?= $cliente['id'] ?? '' ?>">

    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre"
           value="<?= htmlspecialchars($cliente['nombre'] ?? '') ?>" required>

    <label for="apellido">Apellido</label>
    <input type="text" id="apellido" name="apellido"
           value="<?= htmlspecialchars($cliente['apellido'] ?? '') ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email"
           value="<?= htmlspecialchars($cliente['email'] ?? '') ?>" required>

    <label for="telefono">Telefono</label>
    <input type="text" id="telefono" name="telefono"
           value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>">

    <label for="direccion">Direccion</label>
    <input type="text" id="direccion" name="direccion"
           value="<?= htmlspecialchars($cliente['direccion'] ?? '') ?>">

    <label for="clave">
      Contraseña
      <?= $cliente ? '(dejar vacío para no cambiar)' : '' ?>
    </label>
    <input type="password" id="clave" name="clave"
           <?= $cliente ? '' : 'required' ?> minlength="6">

    <button type="submit">Guardar</button>
  </form>

  <p><a href="/admin/clientes">Volver</a></p>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>