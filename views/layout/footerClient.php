</main>

<footer class="pie">
  <div class="footer-redes">
    <a href="https://instagram.com/" target="_blank" title="Instagram">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2">
        <rect x="2" y="2" width="20" height="20" rx="5"/>
        <circle cx="12" cy="12" r="4"/>
        <circle cx="17.5" cy="6.5" r="1" fill="#b56b83"/>
      </svg>
    </a>
    <a href="https://wa.me/541124624235" target="_blank" title="WhatsApp">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2">
        <path d="M21 12a9 9 0 0 1-13.5 7.8L3 21l1.2-4.5A9 9 0 1 1 21 12z"/>
      </svg>
    </a>
    <a href="mailto:administracionpinky@gmail.com" title="Email">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#b56b83" stroke-width="2">
        <rect x="2" y="4" width="20" height="16" rx="2"/>
        <path d="M2 6l10 7 10-7"/>
      </svg>
    </a>
  </div>
  <div class="footer-info">
    <span>Pinky Store</span>
  </div>
</footer>

<script>
function abrirMenuCliente() {
  document.getElementById('menu-cliente').classList.add('abierto');
}
function cerrarMenuCliente() {
  document.getElementById('menu-cliente').classList.remove('abierto');
}
function toggleBuscador() {
  const b = document.getElementById('buscador');
  b.classList.toggle('abierto');
  if (b.classList.contains('abierto')) {
    b.querySelector('input').focus();
  }
}
</script>

<script src="/js/banner.js"></script>
<script src="/js/dolar.js"></script>
<script src="/js/validaciones.js"></script>
<script src="/js/carrito-local.js"></script>

</body>
</html>