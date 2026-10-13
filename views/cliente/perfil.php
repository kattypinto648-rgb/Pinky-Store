<?php require __DIR__ . '/../layout/headerClient.php'; ?>

<section class="perfil-wrapper">
  <div class="perfil-card">
    <div class="perfil-header">
      <div class="perfil-avatar">
        <?= strtoupper(substr($usuario['nombre'], 0, 1) . substr($usuario['apellido'], 0, 1)) ?>
      </div>
      <h2><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></h2>
      <p class="perfil-email"><?= htmlspecialchars($usuario['email']) ?></p>
      <span class="perfil-badge"><?= htmlspecialchars($usuario['rol']) ?></span>
    </div>

    <div class="perfil-datos">
      <div class="perfil-dato">
        <span class="perfil-dato-label">Teléfono</span>
        <span class="perfil-dato-valor"><?= htmlspecialchars($usuario['telefono'] ?: 'Sin cargar') ?></span>
      </div>

      <div class="perfil-dato">
        <span class="perfil-dato-label">Dirección</span>
        <span class="perfil-dato-valor"><?= htmlspecialchars($usuario['direccion'] ?: 'Sin cargar') ?></span>
      </div>

      <div class="perfil-dato">
        <span class="perfil-dato-label">Miembro desde</span>
        <span class="perfil-dato-valor"><?= htmlspecialchars($usuario['creado_en'] ?? 'Hoy') ?></span>
      </div>

      <div class="perfil-dato">
        <span class="perfil-dato-label">Estado</span>
        <span class="perfil-dato-valor"><?= $usuario['activo'] ? 'Activa' : 'Inactiva' ?></span>
      </div>
    </div>

    <div class="perfil-botones">
      <a href="/mis-pedidos" class="perfil-btn">Mis pedidos</a>
      <a href="/logout" class="perfil-btn perfil-btn-outline">Cerrar sesión</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>