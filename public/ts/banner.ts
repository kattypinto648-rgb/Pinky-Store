(function () {
  const imgs: NodeListOf<HTMLImageElement> = document.querySelectorAll('.banner img');
  let pos = 0;

  function mostrar(): void {
    imgs.forEach((img) => img.classList.remove('activa'));
    if (imgs[pos]) imgs[pos].classList.add('activa');
    pos = (pos + 1) % imgs.length;
  }

  if (imgs.length > 0) {
    mostrar();
    setInterval(mostrar, 3000);
  }
})();