<?php
function invalid(array $errors, string $champ): string
{
    return isset($errors[$champ]) ? ' is-invalid' : '';
}
?>
<div class="card shadow-sm mx-auto" style="max-width: 640px;">
    <div class="card-header bg-dark text-white"><h5 class="mb-0"><?= e($title) ?></h5></div>
    <div class="card-body">
        <form method="post" action="<?= e(url('produit', 'save')) ?>" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="code" value="<?= e($produit['code']) ?>">

            <div class="mb-3">
                <label for="designation" class="form-label">Désignation</label>
                <input type="text" class="form-control<?= invalid($errors, 'designation') ?>" id="designation" name="designation" maxlength="250" value="<?= e($produit['designation']) ?>">
                <div class="invalid-feedback"><?= e($errors['designation'] ?? '') ?></div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="prix" class="form-label">Prix (DT)</label>
                    <input type="number" class="form-control<?= invalid($errors, 'prix') ?>" id="prix" name="prix" min="0" value="<?= e($produit['prix']) ?>">
                    <div class="invalid-feedback"><?= e($errors['prix'] ?? '') ?></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="qte" class="form-label">Quantité</label>
                    <input type="number" class="form-control<?= invalid($errors, 'qte') ?>" id="qte" name="qte" min="0" value="<?= e($produit['qte']) ?>">
                    <div class="invalid-feedback"><?= e($errors['qte'] ?? '') ?></div>
                </div>
            </div>
            <div class="mb-3">
                <label for="code_categorie" class="form-label">Catégorie</label>
                <select class="form-select<?= invalid($errors, 'code_categorie') ?>" id="code_categorie" name="code_categorie">
                    <option value="">-- Choisir une catégorie --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= e($c['code']) ?>" <?= (string) $produit['code_categorie'] === (string) $c['code'] ? 'selected' : '' ?>><?= e($c['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= e($errors['code_categorie'] ?? '') ?></div>
            </div>
            <div class="text-end">
                <a href="<?= e(url('produit')) ?>" class="btn btn-secondary">Annuler</a>
                <button class="btn btn-success">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
