<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section class="perfil-admin-wrapper">
  <div class="perfil-admin-card">
    <div class="perfil-admin-header">
      <div class="perfil-admin-avatar">
        <?= strtoupper(substr($usuario['nombre'], 0, 1) . substr($usuario['apellido'], 0, 1)) ?>
      </div>
      <div class="perfil-admin-titulo">
        <h1><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></h1>
        <p><?= htmlspecialchars($usuario['email']) ?></p>
        <span class="perfil-admin-badge">Administrador</span>
      </div>
    </div>

    <div class="perfil-admin-datos">
      <div class="perfil-admin-dato">
        <span class="perfil-admin-icono">
          <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.8 12.8 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.8 12.8 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
          </svg>
        </span>
        <div>
          <span class="perfil-admin-label">Teléfono</span>
          <span class="perfil-admin-valor"><?= htmlspecialchars($usuario['telefono'] ?? 'Sin cargar') ?></span>
        </div>
      </div>

      <div class="perfil-admin-dato">
        <span class="perfil-admin-icono">
          <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
            <circle cx="12" cy="10" r="3"/>
          </svg>
        </span>
        <div>
          <span class="perfil-admin-label">Dirección</span>
          <span class="perfil-admin-valor"><?= htmlspecialchars($usuario['direccion'] ?? 'Sin cargar') ?></span>
        </div>
      </div>

      <div class="perfil-admin-dato">
        <span class="perfil-admin-icono">
          <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </span>
        <div>
          <span class="perfil-admin-label">Miembro desde</span>
          <span class="perfil-admin-valor"><?= htmlspecialchars($usuario['creado_en'] ?? 'Hoy') ?></span>
        </div>
      </div>

      <div class="perfil-admin-dato">
        <span class="perfil-admin-icono">
          <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          </svg>
        </span>
        <div>
          <span class="perfil-admin-label">Rol</span>
          <span class="perfil-admin-valor">Administrador</span>
        </div>
      </div>
    </div>

    <div class="perfil-admin-acciones">
      <a href="/admin/configuracion" class="perfil-admin-btn">Configuración</a>
      <a href="/logout" class="perfil-admin-btn perfil-admin-btn-outline">Cerrar sesión</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>