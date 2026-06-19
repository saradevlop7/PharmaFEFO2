
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('add-stock-form')
    .addEventListener('submit', async (event) => {
      event.preventDefault(); 

      const form    = event.target;
      const errEl   = document.getElementById('stock-error');
      const succEl  = document.getElementById('stock-success');
      errEl.style.display = 'none';
      succEl.style.display = 'none';

      const data = Object.fromEntries(new FormData(form));

      try {
        const res  = await fetch('/api/v1/stock/add', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.success) {
          succEl.textContent = '✅ ' + json.message;
          succEl.style.display = 'block';
          form.reset();
        } else {
          errEl.textContent = '❌ ' + json.message;
          errEl.style.display = 'block';
        }
      } catch (e) {
        errEl.textContent = '❌ Erreur réseau.';
        errEl.style.display = 'block';
      }
    });
});
