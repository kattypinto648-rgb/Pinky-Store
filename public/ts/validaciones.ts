function validarEmail(input: HTMLInputElement): boolean {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(input.value);
}

function validarClave(input: HTMLInputElement): boolean {
  return input.value.length >= 6;
}

document.querySelectorAll('form').forEach((form) => {
  form.addEventListener('submit', (e) => {
    const email = form.querySelector('input[type="email"]') as HTMLInputElement | null;
    const pass  = form.querySelector('input[type="password"]') as HTMLInputElement | null;

    if (email && !validarEmail(email)) {
      e.preventDefault();
      alert('Email invalido');
      return;
    }
    if (pass && !validarClave(pass)) {
      e.preventDefault();
      alert('La contrasena debe tener al menos 6 caracteres');
      return;
    }
  });
});