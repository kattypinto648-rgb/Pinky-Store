<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../middleware/Auth.php';

class ClienteController
{
    public function inicio(array $params = []): void
    {
        $productos = Producto::destacados(4);
        require __DIR__ . '/../../views/cliente/inicio.php';
    }

    public function productos(array $params = []): void
    {
        $busqueda = trim($_GET['busqueda'] ?? '');
        $cat      = trim($_GET['cat'] ?? '');

        if ($busqueda !== '') {
            $productos = Producto::buscar($busqueda);
        } elseif ($cat !== '') {
            $productos = Producto::porCategoria($cat);
        } else {
            $productos = Producto::todos();
        }

        require __DIR__ . '/../../views/cliente/productos.php';
    }

    public function categorias(array $params = []): void
    {
        require __DIR__ . '/../../views/cliente/categorias.php';
    }

    public function nosotros(array $params = []): void
    {
        require __DIR__ . '/../../views/cliente/nosotros.php';
    }

    public function contacto(array $params = []): void
    {
        require __DIR__ . '/../../views/cliente/contacto.php';
    }

    public function perfil(array $params = []): void
    {
        Auth::requiereLogin();

        $usuario = Usuario::buscarPorEmail($_SESSION['usuario']['email']);

        if (!$usuario) {
            header('Location: /logout');
            exit;
        }

        require __DIR__ . '/../../views/cliente/perfil.php';
    }

    public function verCarrito(array $params = []): void
    {
        $items = $_SESSION['carrito'] ?? [];
        $total = 0;
        foreach ($items as $i) {
            $total += $i['subtotal'];
        }
        require __DIR__ . '/../../views/cliente/carrito.php';
    }

    public function agregarAlCarrito(array $params = []): void
    {
        $id    = (int)($_POST['id'] ?? 0);
        $talle = $_POST['talle'] ?? '';

        $prod = Producto::porId($id);
        if (!$prod) {
            $this->responderAgregar(false);
            return;
        }

        $talleValido = false;
        foreach ($prod['variantes'] as $v) {
            if ($v['talle'] === $talle && $v['stock'] > 0) {
                $talleValido = true;
                break;
            }
        }

        if (!$talleValido) {
            $this->responderAgregar(false);
            return;
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $id && $item['talle'] === $talle) {
                $item['cantidad']++;
                $item['subtotal'] = $item['cantidad'] * $item['precio'];
                $this->responderAgregar(true);
                return;
            }
        }
        unset($item);

        $_SESSION['carrito'][] = [
            'id'       => $prod['id'],
            'nombre'   => $prod['nombre'],
            'talle'    => $talle,
            'precio'   => (float)$prod['precio'],
            'cantidad' => 1,
            'subtotal' => (float)$prod['precio'],
        ];

        $this->responderAgregar(true);
    }

    private function responderAgregar(bool $ok): void
    {
        $aceptaJson = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

        if ($aceptaJson) {
            // Liberamos la sesión antes de responder para no bloquear la siguiente request
            session_write_close();
            header('Content-Type: application/json');
            echo json_encode(['ok' => $ok]);
            return;
        }

        header('Location: /carrito');
        exit;
    }

    public function eliminarDelCarrito(array $params = []): void
    {
        $indice = (int)($_POST['indice'] ?? -1);

        if (isset($_SESSION['carrito'][$indice])) {
            array_splice($_SESSION['carrito'], $indice, 1);
        }

        header('Location: /carrito');
        exit;
    }

    public function modificarCantidad(array $params = []): void
    {
        $indice = (int)($_POST['indice'] ?? -1);
        $delta  = (int)($_POST['delta'] ?? 0);

        if (isset($_SESSION['carrito'][$indice])) {
            $item = &$_SESSION['carrito'][$indice];
            $nueva = $item['cantidad'] + $delta;

            if ($nueva < 1) {
                array_splice($_SESSION['carrito'], $indice, 1);
            } else {
                $item['cantidad'] = $nueva;
                $item['subtotal'] = $nueva * $item['precio'];
            }
        }

        header('Location: /carrito');
        exit;
    }

    public function checkout(array $params = []): void
    {
        Auth::requiereLogin();

        $items = $_SESSION['carrito'] ?? [];

        if (empty($items)) {
            header('Location: /carrito');
            exit;
        }

        $total = 0;
        foreach ($items as $i) {
            $total += $i['subtotal'];
        }

        require __DIR__ . '/../../views/cliente/checkout.php';
    }

    public function procesarCheckout(array $params = []): void
    {
        Auth::requiereLogin();

        $items = $_SESSION['carrito'] ?? [];

        if (empty($items)) {
            header('Location: /carrito');
            exit;
        }

        $metodoPago    = trim($_POST['metodo_pago'] ?? '');
        $metodoEntrega = trim($_POST['metodo_entrega'] ?? '');

        $validosPago    = ['Tarjeta de crédito', 'Transferencia', 'Efectivo'];
        $validosEntrega = ['Envío a domicilio', 'Retiro en local'];

        if (!in_array($metodoPago, $validosPago, true) ||
            !in_array($metodoEntrega, $validosEntrega, true)) {
            header('Location: /checkout?error=datos');
            exit;
        }

        $codigo = Pedido::crear(
            (int)$_SESSION['usuario']['id'],
            $items,
            [
                'metodo_pago'    => $metodoPago,
                'metodo_entrega' => $metodoEntrega,
            ]
        );

        unset($_SESSION['carrito']);

        header('Location: /mis-pedidos?ok=' . urlencode($codigo));
        exit;
    }

    public function misPedidos(array $params = []): void
    {
        Auth::requiereLogin();

        $pedidos = Pedido::porUsuario((int)$_SESSION['usuario']['id']);
        $ok      = $_GET['ok'] ?? null;

        require __DIR__ . '/../../views/cliente/mis-pedidos.php';
    }

    public function cancelarPedido(array $params = []): void
    {
        Auth::requiereLogin();

        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $pedido = Pedido::porId($id);

            if ($pedido
                && $pedido['id_usuario'] == $_SESSION['usuario']['id']
                && $pedido['estado'] === 'Pendiente') {
                Pedido::cambiarEstado($id, 'Cancelado');
            }
        }

        header('Location: /mis-pedidos?cancelado=1');
        exit;
    }
}