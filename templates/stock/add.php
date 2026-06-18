<?php require_once dirname(__DIR__, 2) . '/src/Service/AuthService.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>PharmaFEFO — Ajouter un lot</title>
<link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<?php include dirname(__DIR__) . '/layouts/header.php'; ?>
<main class="main">
  <h1>Ajouter un lot</h1>
  <div class="card">
    <div id="stock-error" class="alert-error" style="display:none"></div>
    <div id="stock-success" class="alert-success" style="display:none"></div>
    <form id="add-stock-form">
      <div class="form-grid">
        <div class="field"><label>Médicament *</label><input type="text" name="name" required></div>
        <div class="field"><label>N° de lot *</label><input type="text" name="lot_number" required></div>
        <div class="field"><label>Quantité *</label><input type="number" name="quantity" min="1" required></div>
        <div class="field"><label>Date de péremption *</label><input type="date" name="expiry_date" required></div>
        <div class="field"><label>Fournisseur</label><input type="text" name="supplier"></div>
        <div class="field">
          <label>Catégorie</label>
          <select name="category">
            <option>Antidouleur</option><option>Antibiotique</option>
            <option>Antiviral</option><option>Antihistaminique</option><option>Autre</option>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Ajouter le lot</button>
        <button type="reset" class="btn btn-ghost">Effacer</button>
      </div>
    </form>
  </div>
</main>
<script src="/assets/js/stock.js"></script>
</body>
</html>
