<div class="card shadow-sm mx-auto" style="max-width: 420px;">
    <div class="card-header bg-dark text-white"><h5 class="mb-0">Connexion</h5></div>
    <div class="card-body">
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post" action="<?= e(url('auth', 'authenticate')) ?>">
            <?= csrf_field() ?>
            <div class="mb-3"><label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required></div>
            <div class="mb-3"><label class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="form-control" required></div>
            <button class="btn btn-primary w-100">Se connecter</button>
        </form>
        <p class="small text-muted mt-3 mb-0">Démo : admin@magasin.tn / admin123 &ndash; client@magasin.tn / client123</p>
    </div>
</div>
