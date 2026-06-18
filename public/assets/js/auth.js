async function doLogin() {
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  const errEl = document.getElementById('login-error');

  try {
    const res  = await fetch('/api/v1/auth/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ username, password })
    });
    const data = await res.json();
    if (data.success) {
      const redirects = { PHARMACIEN: '/dashboard', PREPARATEUR: '/stock/add', ADMIN: '/dashboard' };
      window.location.href = redirects[data.role] || '/dashboard';
    } else {
      errEl.textContent = data.message;
      errEl.style.display = 'block';
    }
  } catch (e) {
    errEl.textContent = 'Erreur réseau.';
    errEl.style.display = 'block';
  }
}

document.addEventListener('keydown', e => {
  if (e.key === 'Enter' && document.getElementById('username')) doLogin();
});
