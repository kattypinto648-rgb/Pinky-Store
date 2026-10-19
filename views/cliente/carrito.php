<?php
$titulo = 'Carrito';
require __DIR__ . '/../layout/headerClient.php';
?>

<section>
  <h2>Mi carrito</h2>

  <?php if (isset($_GET['error']) && $_GET['error'] === 'stock'): ?>
    <p class="error">No hay suficiente stock de ese producto.</p>
  <?php endif; ?>

  <div id="carrito-contenido"></div>
</section>

<script>
(function() {
  const logueado = <?= isset($_SESSION['usuario']) ? 'true' : 'false' ?>;
  const itemsSesion = <?= json_encode($items ?? []) ?>;
  const contenedor = document.getElementById('carrito-contenido');

  let items = [];
  let origen = 'sesion';

  if (logueado && itemsSesion.length > 0) {
    items = itemsSesion;
    origen = 'sesion';
  } else {
    try {
      const local = JSON.parse(localStorage.getItem('pinky_carrito') || '[]');
      if (local.length > 0) {
        items = local;
        origen = 'local';
      }
    } catch(e) {}
  }

  if (items.length === 0) {
    contenedor.innerHTML =
      '<div class="carrito-vacio">' +
        '<p>Tu carrito está vacío.</p>' +
        '<a href="/productos" class="boton-agregar" style="display:inline-block;text-decoration:none;padding:10px 20px;">Ir a productos</a>' +
      '</div>';
    return;
  }

  let total = 0;
  let totalItems = 0;
  let filas = '';
  items.forEach(function(item, i) {
    total += item.subtotal;
    totalItems += item.cantidad;

    const precio = Number(item.precio).toLocaleString('es-AR');
    const subtotal = Number(item.subtotal).toLocaleString('es-AR');

    filas += '<tr>' +
      '<td>' + item.nombre + '</td>' +
      '<td>' + item.talle + '</td>' +
      '<td>$ ' + precio + '</td>' +
      '<td>' +
        '<button type="button" class="btn-cantidad" onclick="pinkyCambiarCantidad(' + i + ', -1)">-</button>' +
        '<span class="cantidad-valor">' + item.cantidad + '</span>' +
        '<button type="button" class="btn-cantidad" onclick="pinkyCambiarCantidad(' + i + ', 1)">+</button>' +
      '</td>' +
      '<td>$ ' + subtotal + '</td>' +
      '<td><button type="button" class="btn-eliminar" onclick="pinkyEliminar(' + i + ')">Eliminar</button></td>' +
    '</tr>';
  });

  const totalFormateado = total.toLocaleString('es-AR');

  let resumen = '<p class="carrito-resumen">Tenés <strong>' + totalItems + '</strong> ' +
    (totalItems === 1 ? 'producto' : 'productos') + ' en tu carrito.</p>';

  let botonFinal = '';
  let avisoInvitado = '';

  if (logueado) {
    botonFinal = '<a href="/checkout" class="boton-agregar" style="display:inline-block;text-decoration:none;padding:10px 24px;width:auto;">Finalizar compra</a>';
  } else {
    botonFinal = '<a href="/login" class="boton-agregar" style="display:inline-block;text-decoration:none;padding:10px 24px;width:auto;">Iniciar sesión para comprar</a>';
    avisoInvitado =
      '<div class="carrito-aviso-invitado">' +
        '<p><strong>Estás comprando como invitado.</strong></p>' +
        '<p>Para finalizar tu compra, guardar tus pedidos y ver tu historial, creá tu cuenta o iniciá sesión. ¡Es gratis!</p>' +
        '<div class="carrito-aviso-botones">' +
          '<a href="/login" class="boton-agregar">Iniciar sesión</a>' +
          '<a href="/registro" class="boton-agregar boton-agregar-outline">Crear cuenta</a>' +
        '</div>' +
      '</div>';
  }

  contenedor.innerHTML =
    avisoInvitado +
    resumen +
    '<table>' +
      '<tr><th>Producto</th><th>Talle</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>' +
      filas +
    '</table>' +
    '<div class="carrito-total">' +
      '<span>Total:</span>' +
      '<strong>$ ' + totalFormateado + '</strong>' +
    '</div>' +
    '<div class="carrito-acciones">' +
      '<a href="/productos" class="link-ver-todos">← Seguir comprando</a>' +
      botonFinal +
    '</div>';

  window.pinkyCambiarCantidad = function(i, delta) {
    if (origen === 'local' && window.pinkyCarrito) {
      window.pinkyCarrito.modificarCantidadLocal(i, delta);
      location.reload();
      return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/carrito/cantidad';

    const inp1 = document.createElement('input');
    inp1.type = 'hidden'; inp1.name = 'indice'; inp1.value = i;
    const inp2 = document.createElement('input');
    inp2.type = 'hidden'; inp2.name = 'delta'; inp2.value = delta;

    form.appendChild(inp1);
    form.appendChild(inp2);
    document.body.appendChild(form);
    form.submit();
  };

  window.pinkyEliminar = function(i) {
    if (!confirm('¿Eliminar este producto?')) return;

    if (origen === 'local' && window.pinkyCarrito) {
      window.pinkyCarrito.eliminarLocal(i);
      location.reload();
      return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/carrito/eliminar';

    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'indice'; inp.value = i;

    form.appendChild(inp);
    document.body.appendChild(form);
    form.submit();
  };
})();
</script>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>