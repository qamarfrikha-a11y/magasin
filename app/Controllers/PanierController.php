<?php
class PanierController extends Controller
{
    // GET ?controller=panier&action=index
    public function index(): void
    {
        $detail = Panier::detail(new ProduitModel());
        $this->render('panier/index', ['title' => 'Mon panier'] + $detail);
    }

    // POST : ajoute un produit existant et en stock
    public function ajouter(): void
    {
        $this->requirePost();
        $code = (int) ($_POST['code'] ?? 0);
        $p = (new ProduitModel())->find($code);

        if ($p === null) {
            $this->flash('warning', 'Produit introuvable.');
        } elseif ($p['qte'] <= (Panier::contenu()[$code] ?? 0)) {
            $this->flash('warning', 'Stock maximal atteint pour « ' . $p['designation'] . ' ».');
        } else {
            Panier::ajouter($code);
            $this->flash('success', '« ' . $p['designation'] . ' » ajouté au panier.');
        }
        // Liste blanche de retour : jamais d'URL libre (évite la redirection ouverte)
        $this->redirect(($_POST['retour'] ?? '') === 'panier' ? url('panier') : url('catalogue'));
    }

    public function modifier(): void
    {
        $this->requirePost();
        Panier::modifier((int) ($_POST['code'] ?? 0), (int) ($_POST['quantite'] ?? 0));
        $this->redirect(url('panier'));
    }

    public function retirer(): void
    {
        $this->requirePost();
        Panier::retirer((int) ($_POST['code'] ?? 0));
        $this->redirect(url('panier'));
    }

    // POST : validation de la commande (transaction dans CommandeModel::passer)
    public function valider(): void
    {
        $this->requirePost();
        $this->requireLogin();

        if (Panier::count() === 0) {
            $this->flash('warning', 'Votre panier est vide.');
            $this->redirect(url('panier'));
        }

        try {
            $id = (new CommandeModel())->passer(Auth::user()['id'], Panier::contenu());
            Panier::vider();
            $this->flash('success', "Commande n°$id validée. Merci !");
            $this->redirect(url('commande', 'mes'));
        } catch (RuntimeException $e) {
            // Stock insuffisant : la transaction a été annulée
            $this->flash('danger', $e->getMessage());
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $this->flash('danger', 'Une erreur est survenue, aucune commande enregistrée.');
        }
        $this->redirect(url('panier'));
    }
}
