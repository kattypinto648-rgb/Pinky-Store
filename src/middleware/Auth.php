<?php

class Auth
{
    public static function estaLogueado(): bool
    {
        return isset($_SESSION['usuario']);
    }

    public static function esAdmin(): bool
    {
        return isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin';
    }

    public static function requiereLogin(): void
    {
        if (!self::estaLogueado()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requiereAdmin(): void
    {
        if (!self::esAdmin()) {
            header('Location: /');
            exit;
        }
    }
}