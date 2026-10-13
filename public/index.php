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
    echo "<h1>404 - Página no encontrada</h1>";
    echo "<p>Ruta: <code>" . htmlspecialchars($ruta) . "</code></p>";
    echo "<p><a href='/'>Volver al inicio</a></p>";
}