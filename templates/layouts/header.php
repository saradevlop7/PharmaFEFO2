<?php require_once dirname(__DIR__, 2) . '/src/Service/AuthService.php'; ?>
<header class="header">
  <a class="logo" href="/">Pharma<span>FEFO</span></a>
  <nav class="nav">
    <span class="nav-role"><?= htmlspecialchars(AuthService::getRole() ?? '') ?></span>
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
  </nav>
</header>
