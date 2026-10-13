<?php

require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductoController
{
    public function listar(array $params = []): void
    {
        Auth::requiereAdmin();
        $productos = Producto::todos();
        require __DIR__ . '/../../views/admin/productos.php';
    }

    public function formularioNuevo(array $params = []): void
    {
        Auth::requiereAdmin();
        $producto = null;
        require __DIR__ . '/../../views/admin/producto-form.php';
    }

    public function formularioEditar(array $params = []): void
    {
        Auth::requiereAdmin();
        $id = (int)($params['id'] ?? 0);
        $producto = Producto::porId($id);

        if (!$producto) {
            header('Location: /admin/productos');
            exit;
        }

        require __DIR__ . '/../../views/admin/producto-form.php';
    }

    public function guardar(array $params = []): void
    {
        Auth::requiereAdmin();

        $id          = (int)($_POST['id'] ?? 0);
        $codigo      = trim($_POST['codigo'] ?? '');
        $nombre      = trim($_POST['nombre'] ?? '');
        $categoria   = trim($_POST['categoria'] ?? '');
        $color       = trim($_POST['color'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio      = (float)($_POST['precio'] ?? 0);
        $imagen      = trim($_POST['imagen'] ?? '');

        if ($codigo === '' || $nombre === '' || $categoria === '' || $precio <= 0 || $imagen === '') {
            header('Location: /admin/productos?error=datos');
            exit;
        }

        $db = getDB();

        if ($id === 0) {
            $stmt = $db->prepare('
                INSERT INTO productos (codigo, nombre, descripcion, categoria, color, precio, imagen)
                VALUES (:codigo, :nombre, :descripcion, :categoria, :color, :precio, :imagen)
            ');
            $stmt->execute([
                ':codigo'      => $codigo,
                ':nombre'      => $nombre,
                ':descripcion' => $descripcion,
                ':categoria'   => $categoria,
                ':color'       => $color,
                ':precio'      => $precio,
                ':imagen'      => $imagen,
            ]);
            $idNuevo = (int) $db->lastInsertId();

            $stmtVar = $db->prepare('INSERT INTO variantes (id_producto, talle, stock) VALUES (?, ?, 1)');
            foreach (['XS', 'S', 'M'] as $t) {
                $stmtVar->execute([$idNuevo, $t]);
            }
        } else {
            $stmt = $db->prepare('
                UPDATE productos SET
                  codigo = :codigo,
                  nombre = :nombre,
                  descripcion = :descripcion,
                  categoria = :categoria,
                  color = :color,
                  precio = :precio,
                  imagen = :imagen
                WHERE id = :id
            ');
            $stmt->execute([
                ':codigo'      => $codigo,
                ':nombre'      => $nombre,
                ':descripcion' => $descripcion,
                ':categoria'   => $categoria,
                ':color'       => $color,
                ':precio'      => $precio,
                ':imagen'      => $imagen,
                ':id'          => $id,
            ]);
        }

        header('Location: /admin/productos');
        exit;
    }

    public function eliminar(array $params = []): void
    {
        Auth::requiereAdmin();

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $db = getDB();
            $stmt = $db->prepare('DELETE FROM productos WHERE id = :id');
            $stmt->execute([':id' => $id]);
        }

        header('Location: /admin/productos');
        exit;
    }
}