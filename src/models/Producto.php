<?php

class Producto
{
    public static function todos(): array
    {
        $db = getDB();
        $stmt = $db->query('SELECT * FROM productos WHERE activo = 1 ORDER BY categoria, id');
        $productos = $stmt->fetchAll();

        foreach ($productos as &$p) {
            $p['variantes'] = self::variantesDe((int)$p['id']);
        }
        return $productos;
    }

    public static function buscar(string $texto): array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT * FROM productos
            WHERE activo = 1 AND nombre LIKE :q
            ORDER BY categoria, id
        ');
        $stmt->execute([':q' => '%' . $texto . '%']);
        $productos = $stmt->fetchAll();

        foreach ($productos as &$p) {
            $p['variantes'] = self::variantesDe((int)$p['id']);
        }
        return $productos;
    }

    public static function porCategoria(string $cat): array
    {
        $db = getDB();
        $stmt = $db->prepare('
            SELECT * FROM productos
            WHERE activo = 1 AND categoria = :cat
            ORDER BY id
        ');
        $stmt->execute([':cat' => $cat]);
        $productos = $stmt->fetchAll();

        foreach ($productos as &$p) {
            $p['variantes'] = self::variantesDe((int)$p['id']);
        }
        return $productos;
    }

    public static function porId(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM productos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch();
        if (!$p) return null;
        $p['variantes'] = self::variantesDe((int)$p['id']);
        return $p;
    }

    public static function variantesDe(int $idProducto): array
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT talle, stock FROM variantes WHERE id_producto = :id ORDER BY id');
        $stmt->execute([':id' => $idProducto]);
        return $stmt->fetchAll();
    }

    public static function destacados(int $limite = 4): array
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM productos WHERE activo = 1 ORDER BY id LIMIT :lim');
        $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function enPromocion(): array
    {
        $db = getDB();
        $stmt = $db->query('SELECT * FROM productos WHERE activo = 1 ORDER BY id LIMIT 6');
        $productos = $stmt->fetchAll();

        foreach ($productos as &$p) {
            $p['variantes'] = self::variantesDe((int)$p['id']);
        }
        return $productos;
    }
}