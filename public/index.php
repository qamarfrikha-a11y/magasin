<?php
// Contrôleur frontal : point d'entrée UNIQUE de l'application
require '../app/init.php';

// 1. Lire la route demandée (catalogue/index par défaut)
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'catalogue';
$action         = isset($_GET['action']) ? $_GET['action'] : 'index';

// 2. Liste blanche : nom dans l'URL => classe du contrôleur
$controllers = [
    'catalogue' => 'CatalogueController',
    'produit'   => 'ProduitController',
    'categorie' => 'CategorieController',
    'panier'    => 'PanierController',
    'commande'  => 'CommandeController',
    'auth'      => 'AuthController',
    'admin'     => 'AdminController',
    'api'       => 'ApiController',
];

if (!is_string($controllerName) || !is_string($action) || !isset($controllers[$controllerName])) {
    http_response_code(404);
    exit('Page introuvable');
}

// 3. Protection CSRF centralisée : toute requête POST doit porter le jeton de la session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Csrf::check()) {
    http_response_code(419);
    exit('Jeton CSRF invalide : formulaire expiré ou requête forgée.');
}

$className  = $controllers[$controllerName];
$controller = new $className();

// is_callable() est faux pour les méthodes protected (render, redirect...) : elles ne sont pas des actions
if (!is_callable([$controller, $action])) {
    http_response_code(404);
    exit('Page introuvable');
}

// 4. Exécuter l'action demandée
$controller->$action();
