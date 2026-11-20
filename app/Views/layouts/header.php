<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Magasin Pro') ?> – Magasin Pro</title>
    <link href="assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= e(url('catalogue')) ?>">Magasin Pro</a>
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="<?= e(url('catalogue')) ?>">Catalogue</a></li>
            <?php if ($user && $user['role'] === 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('admin')) ?>">Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('produit')) ?>">Produits</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('categorie')) ?>">Catégories</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('admin', 'commandes')) ?>">Commandes</a></li>
            <?php elseif ($user): ?>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('commande', 'mes')) ?>">Mes commandes</a></li>
            <?php endif; ?>
        </ul>
        <ul class="navbar-nav align-items-md-center">
            <li class="nav-item">
                <a class="nav-link" href="<?= e(url('panier')) ?>">Panier
                    <?php if ($cartCount > 0): ?><span class="badge bg-warning text-dark"><?= (int) $cartCount ?></span><?php endif; ?>
                </a>
            </li>
            <?php if ($user): ?>
                <li class="nav-item text-light small mx-2"><?= e($user['nom']) ?></li>
                <li class="nav-item">
                    <form method="post" action="<?= e(url('auth', 'logout')) ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <button class="btn btn-outline-light btn-sm">Déconnexion</button>
                    </form>
                </li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('auth', 'login')) ?>">Connexion</a></li>
                <li class="nav-item"><a class="btn btn-warning btn-sm" href="<?= e(url('auth', 'register')) ?>">S'inscrire</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<main class="container py-4">
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash[0]) ?> alert-dismissible fade show">
        <?= e($flash[1]) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
