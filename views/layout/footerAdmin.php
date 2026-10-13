  </main>
</div>

<div id="menu-admin" class="menu-lateral">
  <span class="cerrar" onclick="cerrarMenuAdmin()">X</span>
  <div class="menu-usuario">
    <p class="menu-hola">Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></p>
    <p class="menu-rol">Rol: <?= htmlspecialchars($_SESSION['usuario']['rol']) ?></p>
  </div>
  <a href="/admin">Inicio</a>
  <a href="/admin/productos">Productos</a>
  <a href="/admin/pedidos">Pedidos</a>
  <a href="/admin/clientes">Clientes</a>
  <a href="/admin/promociones">Promociones</a>
  <a href="/admin/configuracion">Configuracion</a>
  <a href="/">Ver tienda</a>
  <a href="/logout">Cerrar sesion</a>
</div>

<script>
function abrirMenuAdmin() {
  document.getElementById('menu-admin').classList.add('abierto');
}
function cerrarMenuAdmin() {
  document.getElementById('menu-admin').classList.remove('abierto');
}
function toggleNotificaciones() {
  document.getElementById('panel-notificaciones').classList.toggle('abierto');
  document.getElementById('menu-usuario-admin').classList.remove('abierto');
}
function toggleMenuUsuario() {
  document.getElementById('menu-usuario-admin').classList.toggle('abierto');
  document.getElementById('panel-notificaciones').classList.remove('abierto');
}

function cargarNotificaciones() {
  fetch('/admin/notificaciones')
    .then(function(r) { return r.json(); })
    .then(function(d) {
      var lista = document.getElementById('notif-lista');
      var badge = document.getElementById('notif-badge');
      if (!lista) return;

      if (!d.items || d.items.length === 0) {
        lista.innerHTML = '<li>Sin notificaciones nuevas</li>';
        if (badge) badge.style.display = 'none';
        return;
      }

      var html = '';
      for (var i = 0; i < d.items.length; i++) {
        html += '<li>' + d.items[i].texto + '</li>';
      }
      lista.innerHTML = html;

      if (badge) {
        badge.textContent = d.total;
        badge.style.display = d.total > 0 ? '' : 'none';
      }
    })
    .catch(function() {
      var lista = document.getElementById('notif-lista');
      if (lista) lista.innerHTML = '<li>Error al cargar</li>';
    });
}

cargarNotificaciones();
setInterval(cargarNotificaciones, 60000);
</script>

</body>
</html>