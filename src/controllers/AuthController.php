<?php

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../middleware/Auth.php';

class AuthController
{
    public function mostrarLogin(array $params = []): void
    {
        $error = $_GET['error'] ?? null;
        require __DIR__ . '/../../views/cliente/login.php';
    }

        public function procesarLogin(array $params = []): void
    {
        $email = trim($_POST['email'] ?? '');
        $clave = $_POST['clave'] ?? '';

        if ($email === '' || $clave === '') {
            header('Location: /login?error=campos');
            exit;
        }

        $u = Usuario::buscarPorEmail($email);

        if ($u === null || !password_verify($clave, $u['clave_hash'])) {
            header('Location: /login?error=credenciales');
            exit;
        }

        // Carrito previo de sesión
        $carritoPrevio = $_SESSION['carrito'] ?? [];

        // Carrito de localStorage (enviado desde el form)
        $carritoLocal = json_decode($_POST['carrito_local'] ?? '[]', true) ?: [];

        // Combinar (el local se suma al previo, evitando duplicados)
        foreach ($carritoLocal as $item) {
            $encontrado = false;
            foreach ($carritoPrevio as &$p) {
                if ($p['id'] == $item['id'] && $p['talle'] === $item['talle']) {
                    $p['cantidad'] += (int)($item['cantidad'] ?? 1);
                    $p['subtotal'] = $p['cantidad'] * $p['precio'];
                    $encontrado = true;
                    break;
                }
            }
            unset($p);
            if (!$encontrado) {
                $carritoPrevio[] = $item;
            }
        }

        $_SESSION['usuario'] = [
            'id'       => $u['id'],
            'email'    => $u['email'],
            'nombre'   => $u['nombre'],
            'apellido' => $u['apellido'],
            'rol'      => $u['rol'],
        ];

        $_SESSION['carrito'] = $carritoPrevio;

        // Recordarme
        if (!empty($_POST['recordarme'])) {
            setcookie('pinky_email', $u['email'], time() + (86400 * 30), '/');
        } else {
            setcookie('pinky_email', '', time() - 3600, '/');
        }

        if ($u['rol'] === 'admin') {
            header('Location: /admin');
        } else {
            header('Location: /');
        }
        exit;
    }

    public function mostrarRegistro(array $params = []): void
    {
        $error = $_GET['error'] ?? null;
        require __DIR__ . '/../../views/cliente/registro.php';
    }

    public function procesarRegistro(array $params = []): void
    {
        $nombre    = trim($_POST['nombre']    ?? '');
        $apellido  = trim($_POST['apellido']  ?? '');
        $email     = trim($_POST['email']     ?? '');
        $clave     = $_POST['clave']          ?? '';
        $telefono  = trim($_POST['telefono']  ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if ($nombre === '' || $apellido === '' || $email === '' || $clave === '') {
            header('Location: /registro?error=campos');
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /registro?error=email');
            exit;
        }
        if (strlen($clave) < 6) {
            header('Location: /registro?error=clave');
            exit;
        }
        if (Usuario::existeEmail($email)) {
            header('Location: /registro?error=existe');
            exit;
        }

        Usuario::crear([
            'nombre'    => $nombre,
            'apellido'  => $apellido,
            'email'     => $email,
            'clave'     => $clave,
            'telefono'  => $telefono,
            'direccion' => $direccion,
            'rol'       => 'cliente',
        ]);

        header('Location: /login?ok=registro');
        exit;
    }

    public function logout(array $params = []): void
    {
        // Preservar carrito antes de destruir la sesión
        $carrito = $_SESSION['carrito'] ?? null;

        session_destroy();
        session_start();

        if ($carrito !== null) {
            $_SESSION['carrito'] = $carrito;
        }

        header('Location: /');
        exit;
    }
}