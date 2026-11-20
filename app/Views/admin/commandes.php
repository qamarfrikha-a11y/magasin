<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Commandes</h5>
        <a class="btn btn-outline-light btn-sm" href="<?= e(url('admin', 'exportCsv')) ?>">Exporter en CSV</a>
    </div>
    <table class="table table-hover mb-0">
        <thead><tr><th>N°</th><th>Date</th><th>Client</th><th>Total</th><th>Statut</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($commandes as $c): ?>
            <tr>
                <td><?= (int) $c['id'] ?></td>
                <td><?= e($c['date_commande']) ?></td>
                <td><?= e($c['client']) ?> <small class="text-muted"><?= e($c['email']) ?></small></td>
                <td><?= (int) $c['total'] ?> DT</td>
                <td><span class="badge text-bg-<?= $c['statut'] === 'expediee' ? 'success' : 'warning' ?>"><?= e($c['statut']) ?></span></td>
                <td class="text-end">
                    <?php if ($c['statut'] === 'validee'): ?>
                        <form method="post" action="<?= e(url('admin', 'expedier')) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                            <button class="btn btn-sm btn-success">Marquer expédiée</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
