<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section>
  <div class="admin-seccion-titulo">
    <h2>Clientes</h2>
    <a href="/admin/clientes/nuevo">+ Nuevo cliente</a>
  </div>

  <?php if (empty($clientes)): ?>
    <p>No hay clientes cargados.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th>Telefono</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
      <?php foreach ($clientes as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></td>
          <td><?= htmlspecialchars($c['email']) ?></td>
          <td><?= htmlspecialchars($c['telefono'] ?? '') ?></td>
          <td><?= $c['activo'] ? 'Activo' : 'Inactivo' ?></td>
          <td class="tabla-acciones">
            <a href="/admin/clientes/editar/<?= $c['id'] ?>">Editar</a>

            <form method="POST" action="/admin/clientes/estado" style="display:inline">
              <input type="hidden" name="id" value="<?= $c['id'] ?>">
              <button type="submit"><?= $c['activo'] ? 'Desactivar' : 'Activar' ?></button>
            </form>

            <form method="POST" action="/admin/clientes/eliminar" style="display:inline"
                  onsubmit="return confirm('¿Eliminar este cliente?')">
              <input type="hidden" name="id" value="<?= $c['id'] ?>">
              <button type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>