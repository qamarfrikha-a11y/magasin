<form class="row g-2 mb-4" method="get" action="index.php">
    <input type="hidden" name="controller" value="catalogue">
    <div class="col-md-6">
        <input type="search" id="recherche" name="q" class="form-control" placeholder="Rechercher un produit..." value="<?= e($q) ?>" autocomplete="off">
    </div>
    <div class="col-md-4">
        <select name="cat" id="filtreCat" class="form-select">
            <option value="0">Toutes les catégories</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['code'] ?>" <?= $cat === (int) $c['code'] ? 'selected' : '' ?>><?= e($c['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 d-grid"><button class="btn btn-dark">Filtrer</button></div>
</form>

<div class="row g-3" id="grille"
     data-api="index.php?controller=api&action=produits"
     data-csrf="<?= e(Csrf::token()) ?>"
     data-action="<?= e(url('panier', 'ajouter')) ?>">
    <?php foreach ($produits as $p): ?>
        <div class="col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <span class="badge text-bg-secondary mb-2"><?= e($p['categorie']) ?></span>
                    <h5 class="card-title"><?= e($p['designation']) ?></h5>
                    <p class="fs-4 mb-1"><?= (int) $p['prix'] ?> DT</p>
                    <?php if ($p['qte'] > 0): ?>
                        <p class="text-success small mb-0">En stock (<?= (int) $p['qte'] ?>)</p>
                    <?php else: ?>
                        <p class="text-danger small mb-0">Rupture de stock</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-0">
                    <form method="post" action="<?= e(url('panier', 'ajouter')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="code" value="<?= (int) $p['code'] ?>">
                        <button class="btn btn-primary w-100" <?= $p['qte'] > 0 ? '' : 'disabled' ?>>Ajouter au panier</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$produits): ?><p class="text-muted">Aucun produit trouvé.</p><?php endif; ?>
</div>
