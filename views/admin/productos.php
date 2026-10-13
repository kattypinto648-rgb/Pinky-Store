<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section>
  <div class="admin-seccion-titulo">
    <h2>Productos</h2>
    <a href="/admin/productos/nuevo">+ Nuevo producto</a>
  </div>

  <?php if (empty($productos)): ?>
    <p>No hay productos cargados.</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Categoria</th>
        <th>Precio</th>
        <th>Acciones</th>
      </tr>
      <?php foreach ($productos as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['codigo']) ?></td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['categoria']) ?></td>
          <td>$ <?= number_format($p['precio'], 0, ',', '.') ?></td>
          <td class="tabla-acciones">
            <a href="/admin/productos/editar/<?= $p['id'] ?>">Editar</a>

            <form method="POST" action="/admin/productos/eliminar" style="display:inline"
                  onsubmit="return confirm('¿Eliminar este producto?')">
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