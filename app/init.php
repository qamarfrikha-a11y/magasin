<?php
// Initialisation : chargée une seule fois, par public/index.php
session_start();

require_once '../config/config.php';

// Autoloading : PHP appelle autoload() quand il rencontre une classe inconnue
function autoload(string $className): void
{
    $folders = ['../app/', '../app/Models/', '../app/Controllers/', '../app/Services/'];
    foreach ($folders as $folder) {
        $file = $folder . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}
spl_autoload_register('autoload');

// Helper d'échappement : à utiliser pour TOUTE donnée affichée dans une vue (protection XSS)
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Champ caché CSRF à placer dans chaque formulaire POST
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(Csrf::token()) . '">';
}

// Construit une URL interne : url('produit', 'form', ['code' => 24])
function url(string $controller, string $action = 'index', array $params = []): string
{
    return 'index.php?' . http_build_query(['controller' => $controller, 'action' => $action] + $params);
}
