<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $titulo ?? 'Pinky Store' ?> - Pinky Store</title>
  <link rel="icon" href="/img/iconos/favicon.png">
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>

<header class="pinky-header">
  <div class="header-left">
    <button class="icono-boton" onclick="abrirMenuCliente()" title="Menu">
      <img src="/img/iconos/menu.png" alt="Menu">
    </button>
  </div>

  <div class="logo">
    <a href="/">Pinky Store</a>
  </div>

  <div class="header-right">
    <button class="icono-boton" onclick="toggleBuscador()" title="Buscar">
      <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2" stroke-linecap="round">
        <circle cx="11" cy="11" r="7"></circle>
        <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
      </svg>
    </button>

    <?php if (isset($_SESSION['usuario'])): ?>
      <a href="/perfil" class="icono-boton" title="Mi perfil">
        <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2" stroke-linecap="round">
          <circle cx="12" cy="8" r="4"></circle>
          <path d="M4 21c0-4 4-6 8-6s8 2 8 6"></path>
        </svg>
      </a>
    <?php else: ?>
      <a href="/login" class="icono-boton" title="Acceso">
        <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2" stroke-linecap="round">
          <circle cx="12" cy="8" r="4"></circle>
          <path d="M4 21c0-4 4-6 8-6s8 2 8 6"></path>
        </svg>
      </a>
    <?php endif; ?>

    <?php if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin'): ?>
      <a href="/carrito" class="icono-boton" title="Carrito">
        <svg viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"></path>
        </svg>
        <span id="carrito-badge" class="carrito-badge"></span>
      </a>
    <?php endif; ?>
  </div>

  <div id="buscador" class="buscador">
    <form action="/productos" method="GET">
      <input type="text" name="busqueda" placeholder="Buscar productos...">
      <button type="submit">Buscar</button>
    </form>
  </div>
</header>

<nav class="barra-rosa">
  <a href="/">Inicio</a>
  <a href="/productos">Productos</a>
  <a href="/categorias">Categorias</a>
  <a href="/nosotros">Nosotros</a>
  <a href="/contacto">Contacto</a>
  <?php if (isset($_SESSION['usuario'])): ?>
    <a href="/mis-pedidos">Mis pedidos</a>
  <?php endif; ?>
</nav>

<div id="menu-cliente" class="menu-lateral">
  <span class="cerrar" onclick="cerrarMenuCliente()">X</span>

  <?php if (isset($_SESSION['usuario'])): ?>
    <div class="menu-usuario">
      <p class="menu-hola">Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></p>
      <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
        <p class="menu-rol">Rol: <?= htmlspecialchars($_SESSION['usuario']['rol']) ?></p>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="menu-usuario">
      <p class="menu-hola">Hola, invitado</p>
      <p class="menu-rol">Iniciá sesión para comprar</p>
    </div>
  <?php endif; ?>

  <div class="menu-dolar">
    <span class="menu-dolar-label">Dólar oficial</span>
    <span id="dolar-cliente" class="menu-dolar-valor">Cargando...</span>
  </div>

  <a href="/">Inicio</a>
  <a href="/productos">Productos</a>
  <a href="/categorias">Categorias</a>
  <a href="/nosotros">Nosotros</a>
  <a href="/contacto">Contacto</a>

  <?php if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin'): ?>
    <a href="/carrito">Carrito</a>
  <?php endif; ?>

  <?php if (isset($_SESSION['usuario'])): ?>
    <a href="/mis-pedidos">Mis pedidos</a>
    <a href="/perfil">Mi perfil</a>
    <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
      <a href="/admin">Panel admin</a>
    <?php endif; ?>
    <a href="/logout">Cerrar sesion</a>
  <?php else: ?>
    <a href="/login">Acceso</a>
    <a href="/registro">Registro</a>
  <?php endif; ?>
</div>

<main class="contenido-principal">