# PharmaFEFO — Gestion de Stock Pharmacie

Application web PHP sécurisée de gestion de stock selon la règle **FEFO** (First Expired, First Out).

## Stack
- **Backend** : PHP 8.1+, PDO / MySQL
- **Frontend** : HTML/CSS/JS vanilla (Fetch API, DOM dynamique)
- **Auth** : Sessions PHP + rôles (PREPARATEUR, PHARMACIEN, ADMIN)

## Installation locale

```bash
# 1. Cloner le dépôt
git clone https://github.com/TON_USERNAME/pharmafefo.git
cd pharmafefo

# 2. Copier et remplir le fichier d'environnement
cp .env.example .env
# → éditer .env avec tes identifiants MySQL

# 3. Créer la base de données
mysql -u root -p < database/schema.sql

# 4. Lancer PHP
php -S localhost:8000 -t public/
```

Accède à http://localhost:8000

## Comptes de test (mot de passe : `password`)
| Utilisateur  | Rôle        |
|-------------|-------------|
| admin       | ADMIN       |
| pharmacien  | PHARMACIEN  |
| preparateur | PREPARATEUR |

## Structure
```
pharmafefo/
├── config/
│   └── environment.php     # Variables d'env + mode miroir dev/prod
├── database/
│   ├── database.php        # Singleton PDO
│   └── schema.sql          # Création BDD + seed
├── public/
│   ├── index.php           # Routeur central (point d'entrée unique)
│   ├── .htaccess
│   └── assets/
│       ├── css/app.css
│       └── js/             # auth.js  stock.js  dashboard.js
├── src/
│   ├── Core/Router.php
│   ├── Service/
│   │   ├── AuthService.php
│   │   └── StockService.php
│   └── Controller/
│       ├── Api/            # Renvoient JSON
│       └── Web/            # Renvoient HTML
└── templates/              # Vues PHP
```

## Fonctionnalités
- ✅ **US 1.1** — Ajout de lot asynchrone (Fetch POST, aucun rechargement)
- ✅ **US 2.1** — Filtres dynamiques ("Alerte Rouge", "Attention") reconstruction DOM
- ✅ **US 2.2** — Compteur automatique des produits périmant ce mois
- ✅ **US 3.1** — Délivrance FEFO : lot le plus proche de péremption sorti en premier
- ✅ **US 4.1** — Destruction de lot (status EXPIRED, qty → 0 visuellement)
- ✅ **Secops** — Mode miroir dev/prod (erreurs masquées en production)
- ✅ **Rôles** — 403 automatique si mauvais rôle

## Déploiement (Render / Railway)
1. Push sur `main` → déploiement automatique
2. Configurer les variables d'env sur l'interface de l'hébergeur (pas de `.env` sur GitHub !)
3. Vérifier que `.env` est dans `.gitignore` ✔
