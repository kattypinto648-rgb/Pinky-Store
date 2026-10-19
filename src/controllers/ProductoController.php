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
        $imagenUrl   = trim($_POST['imagen_url'] ?? '');
        $imagenFinal = $imagenUrl;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $dir = __DIR__ . '/../../public/img/productos/';
            if (!is_dir($dir)) mkdir($dir, 0775, true);

            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $nombreArchivo = $codigo . '-' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $nombreArchivo)) {
                    $imagenFinal = '/img/productos/' . $nombreArchivo;
                }
            }
        }

        if ($codigo === '' || $nombre === '' || $categoria === '' || $precio <= 0) {
            header('Location: /admin/productos?error=datos');
            exit;
        }

        if ($imagenFinal === '' && $id === 0) {
            $imagenFinal = '/img/productos/default.png';
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
                ':imagen'      => $imagenFinal,
            ]);
            $id = (int) $db->lastInsertId();
        } else {
            if ($imagenFinal === '') {
                $stmtOld = $db->prepare('SELECT imagen FROM productos WHERE id = :id');
                $stmtOld->execute([':id' => $id]);
                $imagenFinal = $stmtOld->fetchColumn() ?: '/img/productos/default.png';
            }

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
                ':imagen'      => $imagenFinal,
                ':id'          => $id,
            ]);
        }

        $talles = $_POST['talles'] ?? [];

        $stmtDel = $db->prepare('DELETE FROM variantes WHERE id_producto = :id');
        $stmtDel->execute([':id' => $id]);

        $stmtIns = $db->prepare('INSERT INTO variantes (id_producto, talle, stock) VALUES (?, ?, ?)');
        foreach ($talles as $t) {
            $stock = (int)($_POST['stock_' . $t] ?? 0);
            $stmtIns->execute([$id, $t, $stock]);
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
            $stmt = $db->prepare('UPDATE productos SET activo = 0 WHERE id = :id');
            $stmt->execute([':id' => $id]);
        }

        header('Location: /admin/productos');
        exit;
    }
}