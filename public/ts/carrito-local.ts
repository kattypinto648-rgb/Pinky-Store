interface ItemCarrito {
  id: number;
  nombre: string;
  talle: string;
  precio: number;
  cantidad: number;
  subtotal: number;
  stock: number;
}

const CLAVE = 'pinky_carrito';

function leerCarrito(): ItemCarrito[] {
  try {
    const datos = localStorage.getItem(CLAVE);
    return datos ? JSON.parse(datos) : [];
  } catch {
    return [];
  }
}

function guardarCarrito(items: ItemCarrito[]): void {
  localStorage.setItem(CLAVE, JSON.stringify(items));
  actualizarBadge();
}

function limpiarCarrito(): void {
  localStorage.removeItem(CLAVE);
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

function agregarLocal(id: number, nombre: string, talle: string, precio: number, stock: number): boolean {
  const items = leerCarrito();
  const existente = items.find(i => i.id === id && i.talle === talle);

  if (existente) {
    if (existente.cantidad >= existente.stock) return false;
    existente.cantidad++;
    existente.subtotal = existente.cantidad * existente.precio;
  } else {
    if (stock < 1) return false;
    items.push({
      id, nombre, talle, precio,
      cantidad: 1,
      subtotal: precio,
      stock: stock,
    });
  }

  guardarCarrito(items);
  return true;
}

function eliminarLocal(indice: number): void {
  const items = leerCarrito();
  items.splice(indice, 1);
  guardarCarrito(items);
}

function modificarCantidadLocal(indice: number, delta: number): void {
  const items = leerCarrito();
  const item = items[indice];
  if (!item) return;

  const nueva = item.cantidad + delta;

  if (nueva < 1) {
    items.splice(indice, 1);
    guardarCarrito(items);
    return;
  }

  if (nueva > item.stock) return;

  item.cantidad = nueva;
  item.subtotal = nueva * item.precio;
  guardarCarrito(items);
}

document.addEventListener('DOMContentLoaded', actualizarBadge);

(window as any).pinkyCarrito = {
  leerCarrito,
  guardarCarrito,
  limpiarCarrito,
  agregarLocal,
  eliminarLocal,
  modificarCantidadLocal,
  actualizarBadge,
};