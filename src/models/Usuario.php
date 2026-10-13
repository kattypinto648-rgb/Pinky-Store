<?php

class Usuario
{
    public static function buscarPorEmail(string $email): ?array
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = :email AND activo = 1');
        $stmt->execute([':email' => $email]);
        $u = $stmt->fetch();
        return $u ?: null;
    }

    public static function crear(array $datos): int
    {
        $db = getDB();
        $stmt = $db->prepare('
            INSERT INTO usuarios (nombre, apellido, email, clave_hash, telefono, direccion, rol)
            VALUES (:nombre, :apellido, :email, :clave_hash, :telefono, :direccion, :rol)
        ');
        $stmt->execute([
            ':nombre'     => $datos['nombre'],
            ':apellido'   => $datos['apellido'],
            ':email'      => $datos['email'],
            ':clave_hash' => password_hash($datos['clave'], PASSWORD_BCRYPT),
            ':telefono'   => $datos['telefono']  ?? null,
            ':direccion'  => $datos['direccion'] ?? null,
            ':rol'        => $datos['rol']       ?? 'cliente',
        ]);
        return (int) $db->lastInsertId();
    }

    public static function existeEmail(string $email): bool
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }
}