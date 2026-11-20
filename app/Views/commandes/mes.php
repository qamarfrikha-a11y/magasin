<h3 class="mb-3">Mes commandes</h3>
<?php if (!$commandes): ?>
    <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
<?php endif; ?>
<?php foreach ($commandes as $c): ?>
    <div class="card shadow-sm mb-3">
        <div class="card-header d-flex justify-content-between">
            <span>Commande n°<?= (int) $c['id'] ?> &ndash; <?= e($c['date_commande']) ?></span>
            <span class="badge text-bg-<?= $c['statut'] === 'expediee' ? 'success' : 'warning' ?>"><?= e($c['statut']) ?></span>
        </div>
        <ul class="list-group list-group-flush">
            <?php foreach ($c['lignes'] as $l): ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span><?= e($l['designation']) ?> &times; <?= (int) $l['quantite'] ?></span>
                    <span><?= (int) $l['quantite'] * (int) $l['prix_unitaire'] ?> DT</span>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item fw-bold text-end">Total : <?= (int) $c['total'] ?> DT</li>
        </ul>
    </div>
<?php endforeach; ?>
