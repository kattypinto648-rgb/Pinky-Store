<?php require __DIR__ . '/../layout/headerClient.php'; ?>

<section class="centrado">
  <h2>Acceso</h2>

  <?php if (($error ?? null) === 'credenciales'): ?>
    <p class="error">Email o contraseña incorrectos.</p>
  <?php elseif (($error ?? null) === 'campos'): ?>
    <p class="error">Completá todos los campos.</p>
  <?php endif; ?>

  <?php if (isset($_GET['ok']) && $_GET['ok'] === 'registro'): ?>
    <p class="ok">Cuenta creada. Ahora podés iniciar sesión.</p>
  <?php endif; ?>

  <form method="POST" action="/login">
    <label for="email">Correo electrónico</label>
    <input type="email" id="email" name="email" required>

    <label for="clave">Contraseña</label>
    <input type="password" id="clave" name="clave" required>

    <button type="submit">Ingresar</button>
  </form>

  <span><a href="/registro">Registrate</a></span>
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>