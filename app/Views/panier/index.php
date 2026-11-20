<div class="card shadow-sm">
    <div class="card-header bg-dark text-white"><h5 class="mb-0">Mon panier</h5></div>
    <?php if (!$lignes): ?>
        <div class="card-body text-muted">Votre panier est vide. <a href="<?= e(url('catalogue')) ?>">Voir le catalogue</a></div>
    <?php else: ?>
    <table class="table align-middle mb-0">
        <thead><tr><th>Produit</th><th>Prix</th><th style="width:150px">Quantité</th><th>Sous-total</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($lignes as $l): ?>
            <tr>
                <td><?= e($l['designation']) ?></td>
                <td><?= (int) $l['prix'] ?> DT</td>
                <td>
                    <form method="post" action="<?= e(url('panier', 'modifier')) ?>" class="d-flex gap-1">
                        <?= csrf_field() ?>
                        <input type="hidden" name="code" value="<?= (int) $l['code'] ?>">
                        <input type="number" name="quantite" min="0" value="<?= (int) $l['quantite'] ?>" class="form-control form-control-sm">
                        <button class="btn btn-outline-secondary btn-sm">OK</button>
                    </form>
                    <?php if ($l['quantite'] > $l['stock']): ?>
                        <div class="text-danger small">Stock disponible : <?= (int) $l['stock'] ?></div>
                    <?php endif; ?>
                </td>
                <td><?= (int) $l['sous_total'] ?> DT</td>
                <td class="text-end">
                    <form method="post" action="<?= e(url('panier', 'retirer')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="code" value="<?= (int) $l['code'] ?>">
                        <button class="btn btn-outline-danger btn-sm">Retirer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot><tr><th colspan="3" class="text-end">Total</th><th colspan="2"><?= (int) $total ?> DT</th></tr></tfoot>
    </table>
    <div class="card-footer text-end">
        <form method="post" action="<?= e(url('panier', 'valider')) ?>">
            <?= csrf_field() ?>
            <button class="btn btn-success">Valider la commande</button>
        </form>
    </div>
    <?php endif; ?>
</div>
