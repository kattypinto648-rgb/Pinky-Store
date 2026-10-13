interface ItemCarrito {
  id: number;
  nombre: string;
  talle: string;
  precio: number;
  cantidad: number;
  subtotal: number;
}

const CLAVE_CARRITO: string = 'pinky_carrito';

function leerCarrito(): ItemCarrito[] {
  try {
    const datos = localStorage.getItem(CLAVE_CARRITO);
    return datos ? JSON.parse(datos) : [];
  } catch {
    return [];
  }
}

function guardarCarrito(items: ItemCarrito[]): void {
  localStorage.setItem(CLAVE_CARRITO, JSON.stringify(items));
  actualizarBadge();
}

function actualizarBadge(): void {
  const items = leerCarrito();
  const total = items.reduce((s, i) => s + i.cantidad, 0);
  const badge = document.getElementById('carrito-badge');
  if (badge) {
    badge.textContent = total > 0 ? String(total) : '';
  }
}

function mostrarAviso(texto: string): void {
  const previo = document.getElementById('aviso-carrito');
  if (previo) previo.remove();

  const aviso = document.createElement('div');
  aviso.id = 'aviso-carrito';
  aviso.textContent = texto;
  document.body.appendChild(aviso);

  setTimeout(() => aviso.classList.add('visible'), 10);

  setTimeout(() => {
    aviso.classList.remove('visible');
    setTimeout(() => aviso.remove(), 300);
  }, 2000);
}

function agregarAlCarrito(id: number, nombre: string, talle: string, precio: number): void {
  const items = leerCarrito();
  const existente = items.find(i => i.id === id && i.talle === talle);

  if (existente) {
    existente.cantidad++;
    existente.subtotal = existente.cantidad * existente.precio;
  } else {
    items.push({
      id: id,
      nombre: nombre,
      talle: talle,
      precio: precio,
      cantidad: 1,
      subtotal: precio,
    });
  }

  guardarCarrito(items);
  mostrarAviso('✓ ' + nombre + ' agregado al carrito');

  const form = new FormData();
  form.append('id', String(id));
  form.append('talle', talle);

  fetch('/carrito/agregar', {
    method: 'POST',
    headers: { 'Accept': 'application/json' },
    body: form
  }).catch(() => {});
}

function vaciarCarrito(): void {
  localStorage.removeItem(CLAVE_CARRITO);
  actualizarBadge();
}

document.addEventListener('DOMContentLoaded', function () {
  actualizarBadge();
});

(window as any).pinkyCarrito = {
  leerCarrito: leerCarrito,
  guardarCarrito: guardarCarrito,
  agregarAlCarrito: agregarAlCarrito,
  vaciarCarrito: vaciarCarrito,
  actualizarBadge: actualizarBadge,
};