<?php
$titulo = 'Acceso';
require __DIR__ . '/../layout/headerClient.php';
?>

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

  <form method="POST" action="/login" id="form-login">
    <label for="email">Correo electrónico</label>
    <input type="email" id="email" name="email" required
           value="<?= htmlspecialchars($_COOKIE['pinky_email'] ?? '') ?>">

    <label for="clave">Contraseña</label>
    <input type="password" id="clave" name="clave" required>

    <label style="display:flex;align-items:center;gap:8px;margin-top:12px;">
      <input type="checkbox" name="recordarme" value="1" style="width:auto;margin:0;">
      Recordarme
    </label>

    <input type="hidden" name="carrito_local" id="carrito_local" value="">

    <button type="submit">Ingresar</button>
  </form>

  <span><a href="/registro">Registrate</a></span>
</section>

<script>
document.getElementById('form-login').addEventListener('submit', function() {
  try {
    const carrito = localStorage.getItem('pinky_carrito') || '[]';
    document.getElementById('carrito_local').value = carrito;
  } catch(e) {}
});
</script>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>