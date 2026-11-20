<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card shadow-sm kpi"><div class="card-body">
        <div class="text-muted small">Chiffre d'affaires</div><div class="fs-2 fw-semibold"><?= (int) $ca ?> DT</div></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm kpi"><div class="card-body">
        <div class="text-muted small">Commandes</div><div class="fs-2 fw-semibold"><?= (int) $nbCommandes ?></div></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm kpi"><div class="card-body">
        <div class="text-muted small">Clients</div><div class="fs-2 fw-semibold"><?= (int) $nbClients ?></div></div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm"><div class="card-header">Chiffre d'affaires des 14 derniers jours</div>
            <div class="card-body">
                <canvas id="grapheCA" height="110"
                        data-labels="<?= e(json_encode($labels)) ?>"
                        data-values="<?= e(json_encode($valeurs)) ?>"></canvas>
                <?php if (!$labels): ?><p class="text-muted small mb-0">Aucune vente sur la période.</p><?php endif; ?>
            </div></div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm mb-3"><div class="card-header">Top 5 des produits</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($top as $t): ?>
                    <li class="list-group-item d-flex justify-content-between"><?= e($t['designation']) ?><span class="badge text-bg-primary"><?= (int) $t['vendus'] ?></span></li>
                <?php endforeach; ?>
                <?php if (!$top): ?><li class="list-group-item text-muted">Pas encore de ventes.</li><?php endif; ?>
            </ul></div>
        <div class="card shadow-sm"><div class="card-header">Stock faible (≤ <?= (int) Config::STOCK_FAIBLE ?>)</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($stockFaible as $s): ?>
                    <li class="list-group-item d-flex justify-content-between"><?= e($s['designation']) ?>
                        <span class="badge text-bg-<?= $s['qte'] == 0 ? 'danger' : 'warning' ?>"><?= (int) $s['qte'] ?></span></li>
                <?php endforeach; ?>
                <?php if (!$stockFaible): ?><li class="list-group-item text-muted">Aucun.</li><?php endif; ?>
            </ul></div>
    </div>
</div>
<script src="assets/js/chart.umd.js"></script>
