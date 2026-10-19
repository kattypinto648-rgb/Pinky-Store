<?php

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Promocion.php';

class ClienteController
{
    private function bloquearAdmin(): void
    {
        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin') {
            header('Location: /admin');
            exit;
        }
    }

    public function inicio(array $params = []): void
    {
        $productos = Producto::destacados(4);
        require __DIR__ . '/../../views/cliente/inicio.php';
    }

    public function productos(array $params = []): void
    {
        $busqueda = trim($_GET['busqueda'] ?? '');
        $cat      = urldecode(trim($_GET['cat'] ?? ''));
        $min      = $_GET['min'] ?? null;
        $max      = $_GET['max'] ?? null;

        if ($busqueda !== '') {
            $productos = Producto::buscar($busqueda);
        } elseif ($cat !== '') {
            $productos = Producto::porCategoria($cat);
        } else {
            $productos = Producto::todos();
        }

        if ($min !== null && $min !== '') {
            $productos = array_filter($productos, fn($p) => (float)$p['precio'] >= (float)$min);
        }
        if ($max !== null && $max !== '') {
            $productos = array_filter($productos, fn($p) => (float)$p['precio'] <= (float)$max);
        }
        $productos = array_values($productos);

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

        if ($_SESSION['usuario']['rol'] === 'admin') {
            header('Location: /admin');
            exit;
        }

        $usuario = Usuario::buscarPorEmail($_SESSION['usuario']['email']);
        if (!$usuario) {
            header('Location: /logout');
            exit;
        }

        require __DIR__ . '/../../views/cliente/perfil.php';
    }

    public function verCarrito(array $params = []): void
    {
        $this->bloquearAdmin();

        $items = $_SESSION['carrito'] ?? [];
        $total = 0;
        foreach ($items as $i) {
            $total += $i['subtotal'];
        }
        require __DIR__ . '/../../views/cliente/carrito.php';
    }

    public function agregarAlCarrito(array $params = []): void
    {
        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'admin']);
            exit;
        }

        $id    = (int)($_POST['id'] ?? 0);
        $talle = $_POST['talle'] ?? '';

        $prod = Producto::porId($id);
        if (!$prod) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'no existe']);
            exit;
        }

        $stockDisponible = 0;
        foreach ($prod['variantes'] as $v) {
            if ($v['talle'] === $talle) {
                $stockDisponible = (int)$v['stock'];
                break;
            }
        }

        if ($stockDisponible <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'sin stock']);
            exit;
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $id && $item['talle'] === $talle) {
                if ($item['cantidad'] + 1 > $stockDisponible) {
                    header('Content-Type: application/json');
                    echo json_encode(['ok' => false, 'msg' => 'sin stock suficiente']);
                    exit;
                }
                $item['cantidad']++;
                $item['subtotal'] = $item['cantidad'] * $item['precio'];
                header('Content-Type: application/json');
                echo json_encode(['ok' => true]);
                exit;
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

        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }

    public function eliminarDelCarrito(array $params = []): void
    {
        $this->bloquearAdmin();

        $indice = (int)($_POST['indice'] ?? -1);
        if (isset($_SESSION['carrito'][$indice])) {
            array_splice($_SESSION['carrito'], $indice, 1);
        }
        header('Location: /carrito');
        exit;
    }

    public function modificarCantidad(array $params = []): void
    {
        $this->bloquearAdmin();

        $indice = (int)($_POST['indice'] ?? -1);
        $delta  = (int)($_POST['delta'] ?? 0);

        if (isset($_SESSION['carrito'][$indice])) {
            $item = $_SESSION['carrito'][$indice];
            $nueva = $item['cantidad'] + $delta;

            if ($nueva < 1) {
                array_splice($_SESSION['carrito'], $indice, 1);
                header('Location: /carrito');
                exit;
            }

            if ($delta > 0) {
                $prod = Producto::porId((int)$item['id']);
                $stockDisponible = 0;
                if ($prod) {
                    foreach ($prod['variantes'] as $v) {
                        if ($v['talle'] === $item['talle']) {
                            $stockDisponible = (int)$v['stock'];
                            break;
                        }
                    }
                }
                if ($nueva > $stockDisponible) {
                    header('Location: /carrito?error=stock');
                    exit;
                }
            }

            $_SESSION['carrito'][$indice]['cantidad'] = $nueva;
            $_SESSION['carrito'][$indice]['subtotal'] = $nueva * $item['precio'];
        }

        header('Location: /carrito');
        exit;
    }

    public function checkout(array $params = []): void
    {
        $this->bloquearAdmin();
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
        $this->bloquearAdmin();
        Auth::requiereLogin();

        $items=$_SESSION['carrito']?? [];
        
        if(empty($items))
        {
            header('Location: /carrito');
            exit;
        }

        $metodoPago=trim($_POST['metodo_pago'] ?? '');
        $metodoEntrega=trim($_POST['metodo_entrega'] ??' ');
        $codigoPromocion=trim($_POST['codigo_promocion'] ?? '');

        $validosPago = [
            'Tarjeta de crédito',
            'Transferencia',
            'Efectivo'
        ];

        $validosEntrega = [
            'Envío a domicilio',
            'Retiro en local'
        ];

        if (!in_array($metodoPago, $validosPago, true) || !in_array($metodoEntrega, $validosEntrega, true))
        {
            header('Location: /checkout?error=datos');
            exit;
        }

        $porcentajeDescuento=0;
        $codigoAplicado=null;

        if($codigoPromocion!=='')
        {
            $promocion=Promocion::porCodigoActivo($codigoPromocion);

            if(!$promocion)
            {
                header('Location: /checkout?error=promo');
                exit;
            }

            $porcentajeDescuento= (float) $promocion['porcentaje'];
            $codigoAplicado=$promocion['codigo'];
        }

        $codigo=Pedido::crear(
            (int) $_SESSION['usuario']['id'],
            $items,
            [
                'metodo_pago'=>$metodoPago,
                'metodo_entrega'=>$metodoEntrega,
                'codigo_promocion'=>$codigoAplicado,
                'porcentaje_descuento'=>$porcentajeDescuento
            ]
        );

        unset($_SESSION['carrito']);

        header('Location: /mis-pedidos?ok=' . urlencode($codigo));
        exit;
    }

    public function aplicarPromocion(array $params = []): void
    {
        $this->bloquearAdmin();
        Auth::requiereLogin();

        header('Content-Type: application/json; charset=utf-8');

        $items=$_SESSION['carrito'] ?? [];

        if(empty($items))
        {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Tu carrito está vacío.'
            ]);
            exit;
        }

        $codigo= trim($_POST['codigo_promocion'] ?? '');

        if($codigo === '')
        {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Ingresá un códiogo de descuento.'
            ]);
            exit;
        }

        $promocion = Promocion::porCodigoActivo($codigo);

        if(!$promocion)
        {
            echo json_encode([
                'ok' => false,
                'mensaje' => 'El código o la promoción está inactiva.'
            ]);
            exit;
        }

        echo json_encode([
            'ok' => true,
            'codigo' => $promocion['codigo'],
            'porcentaje' => (float) $promocion['porcentaje'],
            'mensaje' => '¡Código aplicado correctamente!'
        ]);
        exit;
    }

    public function misPedidos(array $params = []): void
    {
        $this->bloquearAdmin();
        Auth::requiereLogin();

        $pedidos = Pedido::porUsuario((int)$_SESSION['usuario']['id']);
        $ok      = $_GET['ok'] ?? null;

        require __DIR__ . '/../../views/cliente/mis-pedidos.php';
    }
}