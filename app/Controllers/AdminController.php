<?php
class AdminController extends Controller
{
    private CommandeModel $commandes;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->commandes = new CommandeModel();
    }

    // Tableau de bord
    public function index(): void
    {
        $produits = new ProduitModel();
        $jours = $this->commandes->caParJour(14);

        $this->render('admin/dashboard', [
            'title'        => 'Tableau de bord',
            'ca'           => $this->commandes->chiffreAffaires(),
            'nbCommandes'  => $this->commandes->nbCommandes(),
            'nbClients'    => (new UtilisateurModel())->nbClients(),
            'top'          => $produits->topVendus(5),
            'stockFaible'  => $produits->stockFaible(Config::STOCK_FAIBLE),
            'labels'       => array_column($jours, 'jour'),
            'valeurs'      => array_map('intval', array_column($jours, 'ca')),
        ]);
    }

    public function commandes(): void
    {
        $this->render('admin/commandes', [
            'title'     => 'Commandes',
            'commandes' => $this->commandes->findAllWithClient(),
        ]);
    }

    public function expedier(): void
    {
        $this->requirePost();
        $this->commandes->update((int) ($_POST['id'] ?? 0), ['statut' => 'expediee']);
        $this->flash('success', 'Commande marquée comme expédiée.');
        $this->redirect(url('admin', 'commandes'));
    }

    // Export CSV (séparateur ; et BOM UTF-8 pour une ouverture correcte dans Excel)
    public function exportCsv(): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="commandes_' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['N°', 'Date', 'Client', 'Email', 'Total (DT)', 'Statut'], ';');
        foreach ($this->commandes->findAllWithClient() as $c) {
            fputcsv($out, [$c['id'], $c['date_commande'], $this->csvSafe($c['client']), $this->csvSafe($c['email']), $c['total'], $c['statut']], ';');
        }
        fclose($out);
        exit;
    }

    // Neutralise l'injection de formules Excel (=, +, -, @ en début de cellule)
    private function csvSafe(string $v): string
    {
        return preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v;
    }
}
