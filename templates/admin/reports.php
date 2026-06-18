<?php require_once dirname(__DIR__, 2) . '/src/Service/AuthService.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>PharmaFEFO — Rapports</title>
<link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<?php include dirname(__DIR__) . '/layouts/header.php'; ?>
<main class="main">
  <h1>Rapports des mouvements</h1>
  <div class="card">
    <table>
      <thead><tr><th>Date</th><th>Action</th><th>Médicament</th><th>Lot</th><th>Qté</th><th>Opérateur</th><th>Rôle</th></tr></thead>
      <tbody>
        <?php foreach ($movements as $m): ?>
        <tr>
          <td><?= htmlspecialchars($m['created_at']) ?></td>
          <td><span class="badge <?= $m['action'] === 'ADD' ? 'ok' : ($m['action'] === 'DESTROY' ? 'danger' : 'warn') ?>"><?= $m['action'] ?></span></td>
          <td><?= htmlspecialchars($m['medication_name']) ?></td>
          <td><code><?= htmlspecialchars($m['lot_number']) ?></code></td>
          <td><?= (int)$m['quantity'] ?></td>
          <td><?= htmlspecialchars($m['username']) ?></td>
          <td><?= htmlspecialchars($m['role']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
</body>
</html>
