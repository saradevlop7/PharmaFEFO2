<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PharmaFEFO — <?= $pageTitle ?? 'Stock' ?></title>
<link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<header class="header">
  <div class="logo">Pharma<span>FEFO</span></div>
  <nav class="nav">
    <?php if (AuthService::isLoggedIn()): ?>
      <span class="nav-role"><?= htmlspecialchars(AuthService::getRole()) ?></span>
      <?php if (in_array(AuthService::getRole(), ['PHARMACIEN','ADMIN'])): ?>
        <a href="/dashboard">Dashboard</a>
      <?php endif; ?>
      <?php if (in_array(AuthService::getRole(), ['PREPARATEUR','ADMIN'])): ?>
        <a href="/stock/add">Ajouter lot</a>
      <?php endif; ?>
      <?php if (AuthService::getRole() === 'ADMIN'): ?>
        <a href="/admin/reports">Rapports</a>
      <?php endif; ?>
      <a href="/logout" class="btn-logout">Déconnexion</a>
    <?php endif; ?>
  </nav>
</header>
<main class="main">
  <?= $content ?>
</main>
<script src="/assets/js/auth.js"></script>
</body>
</html>
