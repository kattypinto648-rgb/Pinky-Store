<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$ruta = trim($uri, '/');

$rutas = [
    ['GET',  '',                            'ClienteController',     'inicio'],
    ['GET',  'productos',                   'ClienteController',     'productos'],
    ['GET',  'categorias',                  'ClienteController',     'categorias'],
    ['GET',  'nosotros',                    'ClienteController',     'nosotros'],
    ['GET',  'contacto',                    'ClienteController',     'contacto'],
    ['GET',  'perfil',                      'ClienteController',     'perfil'],
    ['GET',  'carrito',                     'ClienteController',     'verCarrito'],
    ['POST', 'carrito/agregar',             'ClienteController',     'agregarAlCarrito'],
    ['POST', 'carrito/eliminar',            'ClienteController',     'eliminarDelCarrito'],
    ['POST', 'carrito/cantidad',            'ClienteController',     'modificarCantidad'],
    ['GET',  'checkout',                    'ClienteController',     'checkout'],
    ['POST', 'checkout',                    'ClienteController',     'procesarCheckout'],
    ['POST', 'checkout/aplicar-promocion',  'ClienteController',     'aplicarPromocion'],
    ['GET',  'mis-pedidos',                 'ClienteController',     'misPedidos'],

    ['GET',  'login',                       'AuthController',        'mostrarLogin'],
    ['POST', 'login',                       'AuthController',        'procesarLogin'],
    ['GET',  'registro',                    'AuthController',        'mostrarRegistro'],
    ['POST', 'registro',                    'AuthController',        'procesarRegistro'],
    ['GET',  'logout',                      'AuthController',        'logout'],

    ['GET',  'api/dolar',                   'ApiController',         'dolar'],

    ['GET',  'admin',                       'AdminController',       'dashboard'],
    ['GET',  'admin/notificaciones',        'AdminController',       'notificaciones'],
    ['GET',  'admin/configuracion',         'AdminController',       'configuracion'],
    ['POST', 'admin/configuracion/guardar', 'AdminController',       'guardarConfiguracion'],

    ['GET',  'admin/productos',             'ProductoController',    'listar'],
    ['GET',  'admin/productos/nuevo',       'ProductoController',    'formularioNuevo'],
    ['GET',  'admin/productos/editar/:id',  'ProductoController',    'formularioEditar'],
    ['POST', 'admin/productos/guardar',     'ProductoController',    'guardar'],
    ['POST', 'admin/productos/eliminar',    'ProductoController',    'eliminar'],

    ['GET',  'admin/pedidos',               'PedidoController',      'listar'],
    ['POST', 'admin/pedidos/estado',        'PedidoController',      'cambiarEstado'],
    ['POST', 'admin/pedidos/eliminar',      'PedidoController',      'eliminar'],

    ['GET',  'admin/clientes',              'ClienteAdminController','listar'],
    ['GET',  'admin/clientes/nuevo',        'ClienteAdminController','formularioNuevo'],
    ['GET',  'admin/clientes/editar/:id',   'ClienteAdminController','formularioEditar'],
    ['POST', 'admin/clientes/guardar',      'ClienteAdminController','guardar'],
    ['POST', 'admin/clientes/estado',       'ClienteAdminController','cambiarEstado'],
    ['POST', 'admin/clientes/eliminar',     'ClienteAdminController','eliminar'],

    ['GET',  'admin/promociones',           'PromocionController',   'listar'],
    ['GET',  'admin/promociones/nuevo',     'PromocionController',   'formularioNuevo'],
    ['GET',  'admin/promociones/editar/:id','PromocionController',   'formularioEditar'],
    ['POST', 'admin/promociones/guardar',   'PromocionController',   'guardar'],
    ['POST', 'admin/promociones/eliminar',  'PromocionController',   'eliminar'],

    ['GET',  'admin/perfil',                'AdminController',       'perfil'],
];

$metodo = $_SERVER['REQUEST_METHOD'];
$encontrada = false;

foreach ($rutas as $r) {
    [$m, $patron, $controlador, $accion] = $r;
    if ($m !== $metodo) continue;

    $regex = '#^' . preg_replace('#:([a-zA-Z_]+)#', '(?P<$1>[^/]+)', $patron) . '$#';

    if (preg_match($regex, $ruta, $matches)) {
        $encontrada = true;
        $archivoCtrl = __DIR__ . '/../src/controllers/' . $controlador . '.php';

        if (!file_exists($archivoCtrl)) {
            http_response_code(500);
            die("Controlador no encontrado: $controlador");
        }

        require_once $archivoCtrl;
        $instancia = new $controlador();
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        $instancia->$accion($params);
        break;
    }
}

if (!$encontrada) {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>404 - Pinky Store</title>
      <link rel="stylesheet" href="/css/styles.css">
    </head>
    <body>
      <div class="pagina-404">
        <h1>404</h1>
        <p>Uy... esta página no existe.</p>
        <p class="ruta-404">Buscaste: <code><?= htmlspecialchars($ruta) ?></code></p>
        <a href="/" class="boton-agregar" style="display:inline-block;text-decoration:none;padding:12px 24px;">Volver al inicio</a>
      </div>
    </body>
    </html>
    <?php
}