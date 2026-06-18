<?php
require_once dirname(__DIR__) . '/config/environment.php';
require_once dirname(__DIR__) . '/src/Core/Router.php';

$router = new Router();
$method = $_SERVER['REQUEST_METHOD'];

// ── Détecte automatiquement le sous-dossier XAMPP/WAMP ───────────────────
// REQUEST_URI = /pharmaFEFO/public/index.php  →  uri = "/"
// REQUEST_URI = /pharmaFEFO/public/login      →  uri = "/login"
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // ex: /pharmaFEFO/public
$rawPath   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri       = '/' . ltrim(substr($rawPath, strlen($scriptDir)), '/');
if ($uri === '') $uri = '/';

// ── Web routes ─────────────────────────────────────────────────────────────
$router->get('/',              ['Web/AuthController',      'loginPage']);
$router->get('/login',         ['Web/AuthController',      'loginPage']);
$router->get('/logout',        ['Web/AuthController',      'logout']);
$router->get('/dashboard',     ['Web/DashboardController', 'index']);
$router->get('/stock/add',     ['Web/StockController',     'addPage']);
$router->get('/admin/reports', ['Web/AdminController',     'reports']);

// ── API routes ─────────────────────────────────────────────────────────────
$router->post('/api/v1/auth/login',          ['Api/ApiAuthController',  'login']);
$router->post('/api/v1/auth/logout',         ['Api/ApiAuthController',  'logout']);
$router->post('/api/v1/stock/add',           ['Api/ApiStockController', 'add']);
$router->get('/api/v1/batches',              ['Api/ApiStockController', 'batches']);
$router->get('/api/v1/stock/expiring-count', ['Api/ApiStockController', 'expiringCount']);
$router->post('/api/v1/stock/deliver',       ['Api/ApiStockController', 'deliver']);
$router->patch('/api/v1/stock/destroy/{id}', ['Api/ApiStockController', 'destroy']);

$router->dispatch($method, $uri);
