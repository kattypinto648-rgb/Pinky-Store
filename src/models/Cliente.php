<?php

class Cliente
{
    public static function todos(): array
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT u.id, u.nombre, u.apellido, u.email, u.telefono, u.direccion, u.activo
            FROM usuarios u
            WHERE u.rol = 'cliente'
            ORDER BY u.id
        ");
        return $stmt->fetchAll();
    }

    public static function porId(int $id): ?array
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id AND rol = 'cliente'");
        $stmt->execute([':id' => $id]);
        $c = $stmt->fetch();
        return $c ?: null;
    }

    public static function crear(array $d): int
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO usuarios (nombre, apellido, email, clave_hash, telefono, direccion, rol, activo)
            VALUES (:nombre, :apellido, :email, :hash, :telefono, :direccion, 'cliente', 1)
        ");
        $stmt->execute([
            ':nombre'    => $d['nombre'],
            ':apellido'  => $d['apellido'],
            ':email'     => $d['email'],
            ':hash'      => password_hash($d['clave'], PASSWORD_BCRYPT),
            ':telefono'  => $d['telefono']  ?? null,
            ':direccion' => $d['direccion'] ?? null,
        ]);
        return (int) $db->lastInsertId();
    }

    public static function actualizar(int $id, array $d): void
    {
        $db = getDB();
        $sql = "
            UPDATE usuarios
            SET nombre = :nombre,
                apellido = :apellido,
                email = :email,
                telefono = :telefono,
                direccion = :direccion
        ";
        $params = [
            ':nombre'    => $d['nombre'],
            ':apellido'  => $d['apellido'],
            ':email'     => $d['email'],
            ':telefono'  => $d['telefono']  ?? null,
            ':direccion' => $d['direccion'] ?? null,
            ':id'        => $id,
        ];

        // Si se cambió la clave, actualizarla
        if (!empty($d['clave'])) {
            $sql .= ", clave_hash = :hash";
            $params[':hash'] = password_hash($d['clave'], PASSWORD_BCRYPT);
        }

        $sql .= " WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    }

    public static function cambiarActivo(int $id): void
    {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE usuarios
            SET activo = NOT activo
            WHERE id = :id AND rol = 'cliente'
        ");
        $stmt->execute([':id' => $id]);
    }

    public static function eliminar(int $id): void
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id = :id AND rol = 'cliente'");
        $stmt->execute([':id' => $id]);
    }

    public static function existeEmail(string $email, int $exceptoId = 0): bool
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $email, ':id' => $exceptoId]);
        return (int) $stmt->fetchColumn() > 0;
    }
}