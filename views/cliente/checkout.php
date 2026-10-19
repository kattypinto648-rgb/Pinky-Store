
<?php
$titulo = 'Finalizar compra';
require __DIR__ . '/../layout/headerClient.php';
?>

<section class="centrado">
    <h2>Finalizar compra</h2>

    <?php if (($_GET['error'] ?? '') === 'datos'): ?>
        <p class="error">Elegí método de pago y entrega.</p>
    <?php endif; ?>

    <?php if (($_GET['error'] ?? '') === 'promo'): ?>
        <p class="error">El código no existe o la promoción está inactiva.</p>
    <?php endif; ?>

    <form method="POST" action="/checkout">

        <label for="metodo_pago">Método de pago</label>
        <select id="metodo_pago" name="metodo_pago" required>
            <option value="">-- Elegir --</option>
            <option value="Tarjeta de crédito">Tarjeta de crédito</option>
            <option value="Transferencia">Transferencia</option>
            <option value="Efectivo">Efectivo</option>
        </select>

        <label for="metodo_entrega">Método de entrega</label>
        <select id="metodo_entrega" name="metodo_entrega" required>
            <option value="">-- Elegir --</option>
            <option value="Envío a domicilio">Envío a domicilio (+$2.500)</option>
            <option value="Retiro en local">Retiro en local (sin cargo)</option>
        </select>

        <fieldset style="margin-top:20px;border:1px solid #e0d0d6;padding:14px;background:#fff;">
            <legend style="color:#b56b83;font-size:13px;padding:0 6px;">
                Datos de facturación
            </legend>

            <label for="tipo_factura">Tipo de comprobante</label>
            <select id="tipo_factura" name="tipo_factura" onchange="toggleCuit()">
                <option value="Consumidor Final">Consumidor Final</option>
                <option value="Responsable Inscripto">Responsable Inscripto</option>
                <option value="Monotributo">Monotributo</option>
            </select>

            <div id="cuit_wrap" style="display:none;">
                <label for="cuit">CUIT</label>
                <input type="text" id="cuit" name="cuit" placeholder="20-12345678-9">
            </div>

            <label for="razon_social">Razón social (opcional)</label>
            <input type="text" id="razon_social" name="razon_social" placeholder="Mi Empresa S.A.">
        </fieldset>

        <div style="margin-top:20px;padding:15px;border:1px solid #e0d0d6;background:#fff;border-radius:8px;">
            <label for="codigo_promocion">Código de descuento</label>

            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <input
                    type="text"
                    id="codigo_promocion"
                    name="codigo_promocion"
                    placeholder="Ej.: VERANO20"
                    autocomplete="off"
                    style="flex:1;min-width:150px;"
                >

                <button type="button" id="aplicarPromo">
                    Aplicar código
                </button>
            </div>

            <p id="mensajePromo" role="status" aria-live="polite"></p>
        </div>

        <div style="margin-top:20px;">
            <p>Total productos: $ <span id="subtotalProductos"><?= number_format($total, 2, ',', '.') ?></span></p>
            <p>Descuento: $ <span id="montoDescuento">0,00</span></p>
            <p>Costo de envío: $ <span id="costoEnvio">0,00</span></p>

            <p class="precio">
                <strong>Total a pagar: $ <span id="totalFinal"><?= number_format($total, 2, ',', '.') ?></span></strong>
            </p>
        </div>

        <button type="submit">Confirmar compra</button>
    </form>

    <p><a href="/carrito">Volver al carrito</a></p>
</section>

<script>
function toggleCuit() {
    const tipo = document.getElementById('tipo_factura').value;
    const wrap = document.getElementById('cuit_wrap');

    if (!wrap) return;

    wrap.style.display =
        (tipo === 'Consumidor Final') ? 'none' : 'block';
}

const botonPromo = document.getElementById('aplicarPromo');
const inputPromo = document.getElementById('codigo_promocion');
const mensajePromo = document.getElementById('mensajePromo');
const entregaSelect = document.getElementById('metodo_entrega');

const subtotal = <?= json_encode((float) $total) ?>;
let porcentajeDescuento = 0;

function actualizarTotal() {
    const entrega = entregaSelect.value;
    const envio = entrega === 'Envío a domicilio' ? 2500 : 0;

    // El porcentaje se aplica solamente al subtotal de productos.
    const descuento = Math.round(
        subtotal * porcentajeDescuento
    ) / 100;

    const total = Math.max(0, subtotal - descuento + envio);

    const formato = numero => numero.toLocaleString('es-AR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    document.getElementById('subtotalProductos').textContent = formato(subtotal);
    document.getElementById('montoDescuento').textContent = formato(descuento);
    document.getElementById('costoEnvio').textContent = formato(envio);
    document.getElementById('totalFinal').textContent = formato(total);
}

botonPromo.addEventListener('click', async () => {
    const codigo = inputPromo.value.trim();

    if (!codigo) {
        porcentajeDescuento = 0;
        actualizarTotal();
        mensajePromo.textContent = 'Ingresá un código de descuento.';
        return;
    }

    botonPromo.disabled = true;
    mensajePromo.textContent = 'Verificando código...';

    try {
        const respuesta = await fetch('/checkout/aplicar-promocion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                codigo_promocion: codigo
            })
        });

        const resultado = await respuesta.json();

        if (resultado.ok) {
            porcentajeDescuento = Number(resultado.porcentaje) || 0;

            mensajePromo.textContent =
                resultado.mensaje + ' Descuento aplicado: ' +
                porcentajeDescuento + '%.';
        } else {
            porcentajeDescuento = 0;
            mensajePromo.textContent =
                resultado.mensaje || 'No se pudo aplicar el código.';
        }
    } catch (error) {
        porcentajeDescuento = 0;
        mensajePromo.textContent =
            'No se pudo verificar el código. Intentá nuevamente.';
    } finally {
        botonPromo.disabled = false;
        actualizarTotal();
    }
});

entregaSelect.addEventListener('change', actualizarTotal);

inputPromo.addEventListener('input', () => {
    porcentajeDescuento = 0;
    mensajePromo.textContent = '';
    actualizarTotal();
});

actualizarTotal();
</script>

<?php require __DIR__ . '/../layout/footerClient.php'; ?>
