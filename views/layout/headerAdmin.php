<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pinky Store</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>

<div class="admin-topbar">
  <div class="admin-topbar-izq">
    <img src="/img/iconos/menu.png" class="admin-topbar-menu" onclick="abrirMenuAdmin()" alt="Menu">
    <a href="/admin" class="admin-topbar-logo">Pinky Store</a>
  </div>

  <div class="admin-topbar-der">
    <button class="admin-topbar-icono" onclick="toggleNotificaciones()">
      <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.7 21a2 2 0 0 1-3.4 0"/>
      </svg>
      <span class="notif-badge" id="notif-badge">0</span>
    </button>

    <button class="admin-topbar-usuario" onclick="toggleMenuUsuario()">
      <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
      <span class="flecha">▼</span>
    </button>

    <div id="menu-usuario-admin" class="menu-usuario-admin">
      <a href="/perfil">Mi perfil</a>
      <a href="/admin/configuracion">Configuracion</a>
      <a href="/">Ver tienda</a>
      <a href="/logout">Cerrar sesion</a>
    </div>
  </div>
</div>

<div id="panel-notificaciones" class="panel-notificaciones">
  <h3>Notificaciones</h3>
  <ul id="notif-lista">
    <li>Cargando...</li>
  </ul>
</div>

<div class="admin-layout">

  <aside class="admin-menu">
    <nav>
      <a href="/admin">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="admin-icono-link"><path d="M3 9l9-6 9 6v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
        Inicio
      </a>
      <a href="/admin/productos">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="admin-icono-link"><path d="M3 7l9-4 9 4v10l-9 4-9-4z"/></svg>
        Productos
      </a>
      <a href="/admin/pedidos">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="admin-icono-link"><path d="M6 2h12l1 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6z"/><path d="M5 6h14"/></svg>
        Pedidos
      </a>
      <a href="/admin/clientes">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="admin-icono-link"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/></svg>
        Clientes
      </a>
      <a href="/admin/promociones">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="admin-icono-link"><path d="M20 12l-8 8-9-9V4h7z"/></svg>
        Promociones
      </a>
      <a href="/admin/configuracion">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="admin-icono-link"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
        Configuracion
      </a>
    </nav>

    <div class="admin-footer-menu">
      <a href="/">Ver tienda</a>
      <a href="/logout">Cerrar sesion</a>
    </div>
  </aside>

  <main class="admin-contenido">