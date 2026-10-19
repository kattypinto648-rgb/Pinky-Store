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

            $costoEnvio = (
                $datos['metodo_entrega'] === 'Envío a domicilio'
            ) ? 2500.00 : 0.00;
            $porcentaje = (float) ($datos['porcentaje_descuento'] ?? 0);
            $descuento = round($subtotal * $porcentaje / 100, 2);
            $total = max(0, $subtotal + $costoEnvio - $descuento);

            $stmtMax = $db->query("SELECT COUNT(*) FROM pedidos");
            $num = ((int)$stmtMax->fetchColumn()) + 1;
            $codigo = 'FACTURA-' . str_pad((string)$num, 5, '0', STR_PAD_LEFT);

            $stmt = $db->prepare('
                INSERT INTO pedidos(codigo, id_usuario, subtotal, costo_envio, descuento, codigo_promocion, total, metodo_pago, metodo_entrega, estado)
                VALUES(:codigo, :id_usuario, :subtotal, :costo_envio, :descuento, :codigo_promocion, :total, :metodo_pago, :metodo_entrega, "Pendiente")
            ');
            $stmt->execute([
                ':codigo'         => $codigo,
                ':id_usuario'     => $idUsuario,
                ':subtotal'       => $subtotal,
                ':costo_envio'    => $costoEnvio,
                ':descuento'      => $descuento,
                ':codigo_promocion'=>$datos['codigo_promocion'],
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

            // Registrar historial inicial
            self::registrarHistorial($idPedido, 'Pendiente', $idUsuario);

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

    public static function cambiarEstado(int $id, string $estado, ?int $idUsuario = null): void
    {
        $db = getDB();
        $stmt = $db->prepare('UPDATE pedidos SET estado = :estado WHERE id = :id');
        $stmt->execute([':estado' => $estado, ':id' => $id]);

        self::registrarHistorial($id, $estado, $idUsuario);
    }

    public static function eliminar(int $id): void
    {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM pedidos WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    public static function registrarHistorial(int $idPedido, string $estado, ?int $idUsuario = null): void
    {
        $db = getDB();
        $stmt = $db->prepare('
            INSERT INTO pedido_historial (id_pedido, estado, id_usuario)
            VALUES (:id, :estado, :user)
        ');
        $stmt->execute([
            ':id'     => $idPedido,
            ':estado' => $estado,
            ':user'   => $idUsuario,
        ]);
    }

    public static function historial(int $idPedido): array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT h.*, u.nombre AS usuario_nombre
            FROM pedido_historial h
            LEFT JOIN usuarios u ON u.id = h.id_usuario
            WHERE h.id_pedido = :id
            ORDER BY h.fecha DESC
        ');
        $stmt->execute([':id' => $idPedido]);
        return $stmt->fetchAll();
    }
}