<?php

require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Pedido.php';

class PedidoController
{
    public function listar(array $params = []): void
    {
        Auth::requiereAdmin();
        $pedidos = Pedido::todos();
        require __DIR__ . '/../../views/admin/pedidos.php';
    }

    public function detalle(array $params = []): void
    {
        Auth::requiereAdmin();

        $id = (int)($params['id'] ?? 0);
        $pedido = Pedido::porIdConCliente($id);

        if (!$pedido) {
            header('Location: /admin/pedidos');
            exit;
        }

        $pedido['items'] = Pedido::itemsDe($id);

        require __DIR__ . '/../../views/admin/pedido-detalle.php';
    }

    public function cambiarEstado(array $params = []): void
    {
        Auth::requiereAdmin();

        $id     = (int)($_POST['id'] ?? 0);
        $estado = trim($_POST['estado'] ?? '');

        $validos = ['Pendiente', 'Enviado', 'Entregado', 'Cancelado'];

        if ($id > 0 && in_array($estado, $validos, true)) {
            Pedido::cambiarEstado($id, $estado);
        }

        header('Location: /admin/pedidos');
        exit;
    }

    public function eliminar(array $params = []): void
    {
        Auth::requiereAdmin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Pedido::eliminar($id);
        }

        header('Location: /admin/pedidos');
        exit;
    }
}