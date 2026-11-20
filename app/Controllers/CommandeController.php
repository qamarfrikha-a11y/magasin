<?php
// Historique des commandes du client connecté
class CommandeController extends Controller
{
    public function mes(): void
    {
        $this->requireLogin();
        $commandes = (new CommandeModel())->findByUtilisateur(Auth::user()['id']);
        $lignes = new LigneCommandeModel();
        foreach ($commandes as &$c) {
            $c['lignes'] = $lignes->findByCommande((int) $c['id']);
        }
        unset($c);

        $this->render('commandes/mes', ['title' => 'Mes commandes', 'commandes' => $commandes]);
    }
}
