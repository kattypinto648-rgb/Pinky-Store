<?php

class Promocion
{
    public static function todas(): array
    {
        $db = getDB();
        return $db->query('SELECT * FROM promociones ORDER BY id')->fetchAll();
    }

    public static function porId(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM promociones WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch();
        return $p ?: null;
    }

    public static function crear(array $d): void
    {
        $db = getDB();
        $stmt = $db->prepare('
            INSERT INTO promociones (codigo, nombre, porcentaje, activa)
            VALUES (:codigo, :nombre, :porcentaje, :activa)
        ');
        $stmt->execute([
            ':codigo'     => $d['codigo'],
            ':nombre'     => $d['nombre'],
            ':porcentaje' => $d['porcentaje'],
            ':activa'     => $d['activa'] ?? 1,
        ]);
    }

    public static function actualizar(int $id, array $d): void
    {
        $db = getDB();
        $stmt = $db->prepare('
            UPDATE promociones
            SET codigo = :codigo,
                nombre = :nombre,
                porcentaje = :porcentaje,
                activa = :activa
            WHERE id = :id
        ');
        $stmt->execute([
            ':codigo'     => $d['codigo'],
            ':nombre'     => $d['nombre'],
            ':porcentaje' => $d['porcentaje'],
            ':activa'     => $d['activa'] ?? 1,
            ':id'         => $id,
        ]);
    }

    public static function eliminar(int $id): void
    {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM promociones WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}