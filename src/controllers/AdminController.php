<?php

require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Configuracion.php';

class AdminController
{
    public function dashboard(array $params = []): void
    {
        Auth::requiereAdmin();

        $productos = Producto::todos();
        $pedidos   = Pedido::todos();

        $db = getDB();
        $totalClientes = (int) $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn();

        $totalRecaudado = 0;
        foreach ($pedidos as $p) {
            if ($p['estado'] !== 'Cancelado') {
                $totalRecaudado += (float) $p['total'];
            }
        }

        $pedidosRecientes = array_slice($pedidos, 0, 5);
        $productosDestacados = array_slice($productos, 0, 4);

        require __DIR__ . '/../../views/admin/dashboard.php';
    }

    public function configuracion(array $params = []): void
    {
        Auth::requiereAdmin();

        $config = Configuracion::todos();
        $guardado = isset($_GET['ok']);

        require __DIR__ . '/../../views/admin/configuracion.php';
    }

    public function guardarConfiguracion(array $params = []): void
    {
        Auth::requiereAdmin();

        Configuracion::guardar([
            'nombre_tienda'      => trim($_POST['nombre_tienda'] ?? ''),
            'email_contacto'     => trim($_POST['email_contacto'] ?? ''),
            'telefono_contacto'  => trim($_POST['telefono_contacto'] ?? ''),
            'direccion'          => trim($_POST['direccion'] ?? ''),
            'costo_envio'        => trim($_POST['costo_envio'] ?? '0'),
            'envio_gratis_desde' => trim($_POST['envio_gratis_desde'] ?? '0'),
            'mantenimiento'      => isset($_POST['mantenimiento']) ? '1' : '0',
        ]);

        header('Location: /admin/configuracion?ok=1');
        exit;
    }

    public function notificaciones(array $params = []): void
    {
        Auth::requiereAdmin();
        header('Content-Type: application/json');

        $db = getDB();
        $notifs = [];

        $stmt = $db->query("
            SELECT codigo, total FROM pedidos
            WHERE estado = 'Pendiente'
            ORDER BY fecha DESC LIMIT 3
        ");
        foreach ($stmt->fetchAll() as $p) {
            $notifs[] = [
                'tipo'  => 'pedido',
                'texto' => 'Nuevo pedido ' . $p['codigo'] . ' por $' . number_format($p['total'], 0, ',', '.'),
            ];
        }

        $stmt = $db->query("
            SELECT p.nombre, v.talle, v.stock
            FROM variantes v
            JOIN productos p ON p.id = v.id_producto
            WHERE v.stock < 3 AND v.stock > 0
            LIMIT 3
        ");
        foreach ($stmt->fetchAll() as $v) {
            $notifs[] = [
                'tipo'  => 'stock',
                'texto' => 'Stock bajo: ' . $v['nombre'] . ' talle ' . $v['talle'] . ' (' . $v['stock'] . ')',
            ];
        }

        echo json_encode([
            'total' => count($notifs),
            'items' => $notifs,
        ]);
    }
}