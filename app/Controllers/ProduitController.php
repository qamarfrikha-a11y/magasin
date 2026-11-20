<?php
// Administration des produits (rôle admin)
class ProduitController extends Controller
{
    private ProduitModel $produits;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->produits = new ProduitModel();
    }

    // GET ?controller=produit&action=index
    public function index(): void
    {
        $this->render('produits/index', [
            'title'    => 'Gestion des produits',
            'produits' => $this->produits->findAllWithCategorie(),
        ]);
    }

    // GET ?controller=produit&action=form[&code=24]
    public function form(): void
    {
        $produit = ['code' => '', 'designation' => '', 'prix' => '', 'qte' => '', 'code_categorie' => ''];

        if (isset($_GET['code']) && $_GET['code'] !== '') {
            $found = $this->produits->find((int) $_GET['code']);
            if ($found === null) {
                $this->flash('warning', 'Produit introuvable.');
                $this->redirect(url('produit'));
            }
            $produit = $found;
        }
        $this->showForm($produit, []);
    }

    // POST ?controller=produit&action=save
    public function save(): void
    {
        $this->requirePost();

        $produit = [
            'code'           => trim($_POST['code'] ?? ''),
            'designation'    => trim($_POST['designation'] ?? ''),
            'prix'           => trim($_POST['prix'] ?? ''),
            'qte'            => trim($_POST['qte'] ?? ''),
            'code_categorie' => trim($_POST['code_categorie'] ?? ''),
        ];

        $errors = [];
        if ($produit['designation'] === '' || mb_strlen($produit['designation']) > 250) {
            $errors['designation'] = 'Désignation obligatoire (250 caractères maximum).';
        }
        if (filter_var($produit['prix'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
            $errors['prix'] = 'Le prix doit être un entier positif.';
        }
        if (filter_var($produit['qte'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
            $errors['qte'] = 'La quantité doit être un entier positif.';
        }
        if ((new CategorieModel())->find((int) $produit['code_categorie']) === null) {
            $errors['code_categorie'] = 'Choisissez une catégorie existante.';
        }

        if ($errors) {
            $this->showForm($produit, $errors);   // on réaffiche avec les erreurs
            return;
        }

        $nouveau = $produit['code'] === '';
        $this->produits->save($produit);
        $this->flash('success', $nouveau ? 'Produit ajouté avec succès.' : 'Produit modifié avec succès.');
        $this->redirect(url('produit'));
    }

    // POST ?controller=produit&action=delete
    public function delete(): void
    {
        $this->requirePost();
        try {
            $ok = $this->produits->delete((int) ($_POST['code'] ?? 0));
            $ok ? $this->flash('success', 'Produit supprimé avec succès.')
                : $this->flash('warning', 'Produit introuvable.');
        } catch (PDOException $e) {
            // Clé étrangère : le produit figure dans une commande
            $this->flash('danger', 'Suppression impossible : ce produit figure dans des commandes.');
        }
        $this->redirect(url('produit'));
    }

    private function showForm(array $produit, array $errors): void
    {
        $this->render('produits/form', [
            'title'      => $produit['code'] !== '' ? 'Modifier un produit' : 'Ajouter un produit',
            'produit'    => $produit,
            'errors'     => $errors,
            'categories' => (new CategorieModel())->findAll(),
        ]);
    }
}
