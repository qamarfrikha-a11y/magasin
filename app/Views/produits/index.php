<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Gestion des Produits</h5>
        <a href="<?= e(url('produit', 'form')) ?>" class="btn btn-primary btn-sm">Ajouter Produit</a>
    </div>
    <table class="table table-hover mb-0">
        <thead><tr><th>Code</th><th>Désignation</th><th>Prix</th><th>Quantité</th><th>Catégorie</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($produits as $p): ?>
            <tr>
                <td><?= e($p['code']) ?></td>
                <td><?= e($p['designation']) ?></td>
                <td><?= e($p['prix']) ?> DT</td>
                <td><?= e($p['qte']) ?></td>
                <td><?= e($p['categorie']) ?></td>
                <td class="text-end">
                    <a class="btn btn-warning btn-sm" href="<?= e(url('produit', 'form', ['code' => $p['code']])) ?>">Éditer</a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalSupprimer"
                            data-code="<?= e($p['code']) ?>" data-designation="<?= e($p['designation']) ?>">Supprimer</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalSupprimer" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="<?= e(url('produit', 'delete')) ?>">
            <?= csrf_field() ?>
            <div class="modal-header"><h5 class="modal-title">Confirmer la suppression</h5></div>
            <div class="modal-body">
                Supprimer le produit <strong id="modalDesignation"></strong> ?
                <input type="hidden" name="code" id="modalCode">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button class="btn btn-danger">Supprimer</button>
            </div>
        </form>
    </div>
</div>
