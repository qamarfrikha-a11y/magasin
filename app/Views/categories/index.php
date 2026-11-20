<div class="row g-4">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white"><h5 class="mb-0">Catégories</h5></div>
            <table class="table table-hover mb-0">
                <thead><tr><th>Code</th><th>Nom</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><?= e($c['code']) ?></td>
                        <td><?= e($c['nom']) ?></td>
                        <td class="text-end">
                            <a class="btn btn-warning btn-sm" href="<?= e(url('categorie', 'index', ['code' => $c['code']])) ?>">Éditer</a>
                            <form class="d-inline" method="post" action="<?= e(url('categorie', 'delete')) ?>" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="code" value="<?= e($c['code']) ?>">
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0"><?= $edit ? 'Modifier la catégorie' : 'Nouvelle catégorie' ?></h5></div>
            <div class="card-body">
                <form method="post" action="<?= e(url('categorie', 'save')) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="code" value="<?= e($edit['code'] ?? '') ?>">
                    <input class="form-control mb-3" name="nom" maxlength="50" placeholder="Nom" value="<?= e($edit['nom'] ?? '') ?>">
                    <button class="btn btn-success">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
