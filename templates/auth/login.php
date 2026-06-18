<?php $pageTitle = 'Connexion'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>PharmaFEFO — Connexion</title>
<link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="login-body">
<div class="login-card">
  <div class="logo">Pharma<span>FEFO</span></div>
  <p class="login-sub">Gestion de stock — règle FEFO</p>
  <div id="login-error" class="alert-error" style="display:none"></div>
  <div class="field">
    <label>Identifiant</label>
    <input type="text" id="username" placeholder="admin / pharmacien / preparateur">
  </div>
  <div class="field">
    <label>Mot de passe</label>
    <input type="password" id="password" placeholder="password">
  </div>
  <button class="btn btn-primary" onclick="doLogin()">Se connecter</button>
</div>
<script src="/assets/js/auth.js"></script>
</body>
</html>
