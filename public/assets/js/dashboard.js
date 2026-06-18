// Jour 3 US 2.2 + Jour 4 US 2.1 + US 3.1

document.addEventListener('DOMContentLoaded', () => {
  loadExpiringCount(); // US 2.2 — compteur automatique au chargement
  loadBatches('all');  // chargement initial
});

// ── US 2.2 : compteur dynamique ───────────────────────────────────────────
async function loadExpiringCount() {
  const res  = await fetch('/api/v1/stock/expiring-count');
  const data = await res.json();
  if (data.success) {
    document.getElementById('kpi-expiring').textContent = data.count;
  }
}

// ── US 2.1 : filtre dynamique ─────────────────────────────────────────────
function filterBatches(criteria, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  loadBatches(criteria);
}

async function loadBatches(criteria = 'all') {
  const tbody = document.getElementById('batches-tbody');
  tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:2rem">Chargement…</td></tr>';

  const url = '/api/v1/batches' + (criteria !== 'all' ? '?criteria=' + criteria : '');
  const res  = await fetch(url);
  const data = await res.json();

  if (!data.success || !data.data.length) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--muted)">Aucun lot trouvé</td></tr>';
    // update kpi
    document.getElementById('kpi-critical').textContent = '0';
    document.getElementById('kpi-total').textContent    = '0';
    return;
  }

  // Update KPIs
  const critical = data.data.filter(b => daysUntil(b.expiry_date) <= 30).length;
  document.getElementById('kpi-critical').textContent = critical;
  document.getElementById('kpi-total').textContent    = data.data.length;

  // Reconstruct DOM (US 2.1)
  tbody.innerHTML = data.data.map(b => buildRow(b)).join('');
}

function daysUntil(dateStr) {
  const now = new Date(); now.setHours(0,0,0,0);
  return Math.floor((new Date(dateStr) - now) / 86400000);
}

function buildRow(b) {
  const d     = daysUntil(b.expiry_date);
  const badge = d < 0   ? '<span class="badge danger">Périmé</span>'
              : d <= 30 ? '<span class="badge danger">🔴 Critique</span>'
              : d <= 90 ? '<span class="badge warn">🟡 Attention</span>'
              :           '<span class="badge ok">✅ OK</span>';

  return `<tr id="row-${b.id}">
    <td><strong>${escHtml(b.name)}</strong><br><small style="color:var(--muted)">${escHtml(b.category||'')}</small></td>
    <td><code style="font-size:.78rem">${escHtml(b.lot_number)}</code></td>
    <td>${b.expiry_date}<br><small style="color:${d<=30?'var(--red)':d<=90?'var(--yellow)':'var(--muted)'}">J${d>=0?'+':''}${d}</small></td>
    <td id="qty-${b.id}"><strong>${b.quantity}</strong></td>
    <td>${badge}</td>
    <td>
      <div style="display:flex;gap:.4rem">
        <button class="btn btn-primary btn-sm" onclick="deliverOne(${b.id},'${escHtml(b.name)}')">💊 Délivrer</button>
        <button class="btn btn-danger btn-sm"  onclick="destroyBatch(${b.id})">🗑 Détruire</button>
      </div>
    </td>
  </tr>`;
}

// ── US 3.1 : délivrance FEFO ──────────────────────────────────────────────
async function deliverOne(id, name) {
  const res  = await fetch('/api/v1/stock/deliver', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ medication_name: name })
  });
  const data = await res.json();
  if (!data.success) { alert(data.message); return; }

  const newQty = data.data.new_qty;
  const qtyEl  = document.getElementById('qty-' + id);
  if (qtyEl) qtyEl.innerHTML = '<strong>' + newQty + '</strong>';

  if (newQty === 0) {
    // Griser puis supprimer la ligne (US 3.1)
    const row = document.getElementById('row-' + id);
    if (row) { row.classList.add('row-expired'); setTimeout(() => row.remove(), 1200); }
  }
}

// ── US 4.1 : détruire un lot ──────────────────────────────────────────────
async function destroyBatch(id) {
  if (!confirm('Marquer ce lot comme détruit ?')) return;
  const res  = await fetch('/api/v1/stock/destroy/' + id, { method: 'PATCH' });
  const data = await res.json();
  if (!data.success) { alert(data.message); return; }

  const row = document.getElementById('row-' + id);
  if (row) {
    row.classList.add('row-expired');
    row.querySelector('td:nth-child(4)').innerHTML = '<strong>0</strong>';
    row.querySelector('.btn-danger').remove();
  }
}

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
