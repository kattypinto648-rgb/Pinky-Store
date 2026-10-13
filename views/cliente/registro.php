<?php require __DIR__ . '/../layout/headerClient.php'; ?>

<section class="centrado">
  <h2>Registro</h2>

  <?php if (($error ?? null) === 'email'): ?>
    <p class="error">El email no tiene formato válido.</p>
  <?php elseif (($error ?? null) === 'clave'): ?>
    <p class="error">La contraseña debe tener al menos 6 caracteres.</p>
  <?php elseif (($error ?? null) === 'existe'): ?>
    <p class="error">Ese email ya está registrado.</p>
  <?php elseif (($error ?? null) === 'campos'): ?>
    <p class="error">Completá todos los campos obligatorios.</p>
  <?php endif; ?>

  <form method="POST" action="/registro">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="apellido">Apellido</label>
    <input type="text" id="apellido" name="apellido" required>

    <label for="email">Correo electrónico</label>
    <input type="email" id="email" name="email" required>

    <label for="clave">Contraseña</label>
    <input type="password" id="clave" name="clave" minlength="6" required>

    <label for="telefono">Teléfono (opcional)</label>
    <input type="text" id="telefono" name="telefono">

    <label for="direccion">Dirección (opcional)</label>
    <input type="text" id="direccion" name="direccion">

    <button type="submit">Registrarme</button>
  </form>

  <span><a href="/login">Ya tengo cuenta</a></span>
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>