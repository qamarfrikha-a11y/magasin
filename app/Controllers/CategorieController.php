<?php
// Administration des catégories (rôle admin)
class CategorieController extends Controller
{
    private CategorieModel $categories;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->categories = new CategorieModel();
    }

    public function index(): void
    {
        $edit = isset($_GET['code']) ? $this->categories->find((int) $_GET['code']) : null;
        $this->render('categories/index', [
            'title'      => 'Gestion des catégories',
            'categories' => $this->categories->findAll(),
            'edit'       => $edit,
        ]);
    }

    public function save(): void
    {
        $this->requirePost();
        $nom = trim($_POST['nom'] ?? '');
        if ($nom === '' || mb_strlen($nom) > 50) {
            $this->flash('danger', 'Le nom est obligatoire (50 caractères maximum).');
        } else {
            $this->categories->save(['code' => trim($_POST['code'] ?? ''), 'nom' => $nom]);
            $this->flash('success', 'Catégorie enregistrée.');
        }
        $this->redirect(url('categorie'));
    }

    public function delete(): void
    {
        $this->requirePost();
        $code = (int) ($_POST['code'] ?? 0);
        if ($this->categories->nbProduits($code) > 0) {
            $this->flash('danger', 'Suppression impossible : la catégorie contient des produits.');
        } else {
            $this->categories->delete($code);
            $this->flash('success', 'Catégorie supprimée.');
        }
        $this->redirect(url('categorie'));
    }
}
