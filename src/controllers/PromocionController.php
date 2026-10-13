<?php

require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Promocion.php';

class PromocionController
{
    public function listar(array $params = []): void
    {
        Auth::requiereAdmin();
        $promociones = Promocion::todas();
        require __DIR__ . '/../../views/admin/promociones.php';
    }

    public function formularioNuevo(array $params = []): void
    {
        Auth::requiereAdmin();
        $promo = null;
        require __DIR__ . '/../../views/admin/promocion-form.php';
    }

    public function formularioEditar(array $params = []): void
    {
        Auth::requiereAdmin();
        $id = (int)($params['id'] ?? 0);
        $promo = Promocion::porId($id);
        if (!$promo) {
            header('Location: /admin/promociones');
            exit;
        }
        require __DIR__ . '/../../views/admin/promocion-form.php';
    }

    public function guardar(array $params = []): void
    {
        Auth::requiereAdmin();

        $id         = (int)($_POST['id'] ?? 0);
        $codigo     = trim($_POST['codigo'] ?? '');
        $nombre     = trim($_POST['nombre'] ?? '');
        $porcentaje = (int)($_POST['porcentaje'] ?? 0);
        $activa     = isset($_POST['activa']) ? 1 : 0;

        if ($codigo === '' || $nombre === '' || $porcentaje < 1 || $porcentaje > 100) {
            header('Location: /admin/promociones?error=datos');
            exit;
        }

        $datos = [
            'codigo'     => $codigo,
            'nombre'     => $nombre,
            'porcentaje' => $porcentaje,
            'activa'     => $activa,
        ];

        if ($id === 0) {
            Promocion::crear($datos);
        } else {
            Promocion::actualizar($id, $datos);
        }

        header('Location: /admin/promociones');
        exit;
    }

    public function eliminar(array $params = []): void
    {
        Auth::requiereAdmin();
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Promocion::eliminar($id);
        }
        header('Location: /admin/promociones');
        exit;
    }
}