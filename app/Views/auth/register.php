<div class="card shadow-sm mx-auto" style="max-width: 420px;">
    <div class="card-header bg-dark text-white"><h5 class="mb-0">Créer un compte</h5></div>
    <div class="card-body">
        <form method="post" action="<?= e(url('auth', 'store')) ?>" novalidate>
            <?= csrf_field() ?>
            <?php foreach ([['nom', 'Nom', 'text'], ['email', 'Email', 'email'], ['mot_de_passe', 'Mot de passe (6 caractères min.)', 'password']] as [$champ, $label, $type]): ?>
                <div class="mb-3">
                    <label class="form-label"><?= e($label) ?></label>
                    <input type="<?= $type ?>" name="<?= $champ ?>" class="form-control<?= isset($errors[$champ]) ? ' is-invalid' : '' ?>"
                           value="<?= $type === 'password' ? '' : e($old[$champ]) ?>">
                    <div class="invalid-feedback"><?= e($errors[$champ] ?? '') ?></div>
                </div>
            <?php endforeach; ?>
            <button class="btn btn-primary w-100">S'inscrire</button>
        </form>
    </div>
</div>
