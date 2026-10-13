<?php require __DIR__ . '/../layout/headerAdmin.php'; ?>

<section>
  <h2>Configuración</h2>

  <?php if ($guardado): ?>
    <p class="ok">Cambios guardados correctamente.</p>
  <?php endif; ?>

  <form method="POST" action="/admin/configuracion/guardar" style="max-width:100%;">

    <h3 style="color:#b56b83;font-size:14px;margin-top:0;">Datos de la tienda</h3>

    <label for="nombre_tienda">Nombre</label>
    <input type="text" id="nombre_tienda" name="nombre_tienda"
           value="<?= htmlspecialchars($config['nombre_tienda'] ?? '') ?>">

    <label for="email_contacto">Email de contacto</label>
    <input type="email" id="email_contacto" name="email_contacto"
           value="<?= htmlspecialchars($config['email_contacto'] ?? '') ?>">

    <label for="telefono_contacto">Teléfono</label>
    <input type="text" id="telefono_contacto" name="telefono_contacto"
           value="<?= htmlspecialchars($config['telefono_contacto'] ?? '') ?>">

    <label for="direccion">Dirección</label>
    <input type="text" id="direccion" name="direccion"
           value="<?= htmlspecialchars($config['direccion'] ?? '') ?>">

    <h3 style="color:#b56b83;font-size:14px;margin-top:24px;">Envíos</h3>

    <label for="costo_envio">Costo de envío ($)</label>
    <input type="number" id="costo_envio" name="costo_envio"
           value="<?= htmlspecialchars($config['costo_envio'] ?? '0') ?>">

    <label for="envio_gratis_desde">Envío gratis desde ($)</label>
    <input type="number" id="envio_gratis_desde" name="envio_gratis_desde"
           value="<?= htmlspecialchars($config['envio_gratis_desde'] ?? '0') ?>">

    <label style="margin-top:20px;display:flex;align-items:center;gap:8px;">
      <input type="checkbox" name="mantenimiento" value="1"
             <?= ($config['mantenimiento'] ?? '0') === '1' ? 'checked' : '' ?>
             style="width:auto;margin:0;">
      Modo mantenimiento (la tienda queda fuera de servicio)
    </label>

    <button type="submit">Guardar cambios</button>
  </form>
</section>

<?php require __DIR__ . '/../layout/footerAdmin.php'; ?>