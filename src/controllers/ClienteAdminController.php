<?php

require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteAdminController
{
    public function listar(array $params = []): void
    {
        Auth::requiereAdmin();
        $clientes = Cliente::todos();
        require __DIR__ . '/../../views/admin/clientes.php';
    }

    public function formularioNuevo(array $params = []): void
    {
        Auth::requiereAdmin();
        $cliente = null;
        require __DIR__ . '/../../views/admin/cliente-form.php';
    }

    public function formularioEditar(array $params = []): void
    {
        Auth::requiereAdmin();
        $id = (int)($params['id'] ?? 0);
        $cliente = Cliente::porId($id);
        if (!$cliente) {
            header('Location: /admin/clientes');
            exit;
        }
        require __DIR__ . '/../../views/admin/cliente-form.php';
    }

    public function guardar(array $params = []): void
    {
        Auth::requiereAdmin();

        $id        = (int)($_POST['id'] ?? 0);
        $nombre    = trim($_POST['nombre']    ?? '');
        $apellido  = trim($_POST['apellido']  ?? '');
        $email     = trim($_POST['email']     ?? '');
        $telefono  = trim($_POST['telefono']  ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $clave     = $_POST['clave'] ?? '';

        if ($nombre === '' || $apellido === '' || $email === '') {
            header('Location: /admin/clientes?error=campos');
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /admin/clientes?error=email');
            exit;
        }
        if (Cliente::existeEmail($email, $id)) {
            header('Location: /admin/clientes?error=existe');
            exit;
        }

        $datos = [
            'nombre'    => $nombre,
            'apellido'  => $apellido,
            'email'     => $email,
            'telefono'  => $telefono,
            'direccion' => $direccion,
            'clave'     => $clave,
        ];

        if ($id === 0) {
            if ($clave === '') {
                header('Location: /admin/clientes?error=clave');
                exit;
            }
            Cliente::crear($datos);
        } else {
            Cliente::actualizar($id, $datos);
        }

        header('Location: /admin/clientes');
        exit;
    }

    public function cambiarEstado(array $params = []): void
    {
        Auth::requiereAdmin();
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Cliente::cambiarActivo($id);
        }
        header('Location: /admin/clientes');
        exit;
    }

    public function eliminar(array $params = []): void
    {
        Auth::requiereAdmin();
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Cliente::eliminar($id);
        }
        header('Location: /admin/clientes');
        exit;
    }
}