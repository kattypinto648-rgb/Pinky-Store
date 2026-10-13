<?php

class Pedido
{
    public static function crear(int $idUsuario, array $carrito, array $datos): string
    {
        $db = getDB();
        $db->beginTransaction();

        try {
            $subtotal = 0;
            foreach ($carrito as $item) {
                $subtotal += $item['subtotal'];
            }

            $costoEnvio = ($datos['metodo_entrega'] === 'Envío a domicilio') ? 2500.00 : 0.00;
            $total = $subtotal + $costoEnvio;

            // Código de factura correlativo
            $stmtMax = $db->query("SELECT COUNT(*) FROM pedidos");
            $num = ((int)$stmtMax->fetchColumn()) + 1;
            $codigo = 'FACTURA-' . str_pad((string)$num, 5, '0', STR_PAD_LEFT);

            $stmt = $db->prepare('
                INSERT INTO pedidos
                  (codigo, id_usuario, subtotal, costo_envio, total, metodo_pago, metodo_entrega, estado)
                VALUES
                  (:codigo, :id_usuario, :subtotal, :costo_envio, :total, :metodo_pago, :metodo_entrega, "Pendiente")
            ');
            $stmt->execute([
                ':codigo'         => $codigo,
                ':id_usuario'     => $idUsuario,
                ':subtotal'       => $subtotal,
                ':costo_envio'    => $costoEnvio,
                ':total'          => $total,
                ':metodo_pago'    => $datos['metodo_pago'],
                ':metodo_entrega' => $datos['metodo_entrega'],
            ]);
            $idPedido = (int) $db->lastInsertId();

            $stmtDet = $db->prepare('
                INSERT INTO detalle_pedido (id_pedido, id_producto, talle, cantidad, precio_unit)
                VALUES (:id_pedido, :id_producto, :talle, :cantidad, :precio_unit)
            ');
            foreach ($carrito as $item) {
                $stmtDet->execute([
                    ':id_pedido'    => $idPedido,
                    ':id_producto'  => $item['id'],
                    ':talle'        => $item['talle'],
                    ':cantidad'     => $item['cantidad'],
                    ':precio_unit'  => $item['precio'],
                ]);
            }

            $db->commit();
            return $codigo;

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function porUsuario(int $idUsuario): array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT * FROM pedidos
            WHERE id_usuario = :id
            ORDER BY fecha DESC
        ');
        $stmt->execute([':id' => $idUsuario]);
        $pedidos = $stmt->fetchAll();

        foreach ($pedidos as &$p) {
            $p['items'] = self::itemsDe((int)$p['id']);
        }
        return $pedidos;
    }

    public static function todos(): array
    {
        $db = getDB();
        $stmt = $db->query('
            SELECT p.*, u.nombre AS cliente_nombre, u.apellido AS cliente_apellido
            FROM pedidos p
            JOIN usuarios u ON u.id = p.id_usuario
            ORDER BY p.fecha DESC
        ');
        return $stmt->fetchAll();
    }

    public static function itemsDe(int $idPedido): array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT d.*, p.nombre AS producto_nombre
            FROM detalle_pedido d
            JOIN productos p ON p.id = d.id_producto
            WHERE d.id_pedido = :id
        ');
        $stmt->execute([':id' => $idPedido]);
        return $stmt->fetchAll();
    }

    public static function cambiarEstado(int $id, string $estado): void
    {
        $db = getDB();
        $stmt = $db->prepare('UPDATE pedidos SET estado = :estado WHERE id = :id');
        $stmt->execute([':estado' => $estado, ':id' => $id]);
    }

    public static function eliminar(int $id): void
    {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM pedidos WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
        public static function porIdConCliente(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT p.*,
                   u.nombre AS cliente_nombre,
                   u.apellido AS cliente_apellido,
                   u.email AS cliente_email
            FROM pedidos p
            JOIN usuarios u ON u.id = p.id_usuario
            WHERE p.id = :id
        ');
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch();
        return $p ?: null;
    }
}