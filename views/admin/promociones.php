<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section>
  <div class="admin-seccion-titulo">
    <h2>Promociones</h2>
    <a href="/admin/promociones/nuevo">+ Nueva promocion</a>
  </div>

  <?php if (empty($promociones)): ?>
    <p>No hay promociones cargadas.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Descuento</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
      <?php foreach ($promociones as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['codigo']) ?></td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= (int)$p['porcentaje'] ?>%</td>
          <td><?= $p['activa'] ? 'Activa' : 'Inactiva' ?></td>
          <td class="tabla-acciones">
            <a href="/admin/promociones/editar/<?= $p['id'] ?>">Editar</a>
            <form method="POST" action="/admin/promociones/eliminar" style="display:inline"
                  onsubmit="return confirm('¿Eliminar esta promocion?')">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>