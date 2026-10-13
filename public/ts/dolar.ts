"use strict";
async function cargarDolar() {
    const el = document.getElementById('dolar-cliente') || document.getElementById('dolar-admin');
    if (!el) return;
    try {
        const r = await fetch('/api/dolar');
        const d = await r.json();
        if (d && d.venta) {
            el.textContent = '$ ' + d.venta.toFixed(2);
        } else {
            el.textContent = 'No disponible';
        }
    } catch {
        el.textContent = 'Error';
    }
}
cargarDolar();