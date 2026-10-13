<?php

class Configuracion
{
    public static function todos(): array
    {
        $db = getDB();
        $filas = $db->query('SELECT clave, valor FROM configuracion')->fetchAll();
        $res = [];
        foreach ($filas as $f) {
            $res[$f['clave']] = $f['valor'];
        }
        return $res;
    }

    public static function guardar(array $datos): void
    {
        $db = getDB();
        $stmt = $db->prepare('UPDATE configuracion SET valor = :valor WHERE clave = :clave');
        foreach ($datos as $clave => $valor) {
            $stmt->execute([':clave' => $clave, ':valor' => $valor]);
        }
    }
}