<?php
require_once dirname(__DIR__, 2) . '/src/Service/AuthService.php';
$pageTitle = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>PharmaFEFO — Dashboard</title>
<link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<?php include dirname(__DIR__) . '/layouts/header.php'; ?>
<main class="main">
  <h1>Dashboard</h1>

  <div class="kpi-grid">
    <div class="kpi danger"><div class="val" id="kpi-expiring">…</div><div class="lbl">Périmant ce mois</div></div>
    <div class="kpi warn"><div class="val" id="kpi-critical">…</div><div class="lbl">Lots critiques</div></div>
    <div class="kpi ok"><div class="val" id="kpi-total">…</div><div class="lbl">Lots actifs</div></div>
  </div>

  <div class="card">
    <div class="card-header">
      <h2>Stock</h2>
      <div class="filter-bar">
        <button class="filter-btn active" onclick="filterBatches('all', this)">Tous</button>
        <button class="filter-btn red"    onclick="filterBatches('critical', this)">🔴 Alerte Rouge</button>
        <button class="filter-btn yellow" onclick="filterBatches('expiring', this)">🟡 Attention</button>
      </div>
    </div>
    <table>
      <thead>
        <tr><th>Médicament</th><th>Lot</th><th>Péremption</th><th>Qté</th><th>Statut</th><th>Action</th></tr>
      </thead>
      <tbody id="batches-tbody"><tr><td colspan="6">Chargement…</td></tr></tbody>
    </table>
  </div>
</main>
<script src="/assets/js/dashboard.js"></script>
</body>
</html>
