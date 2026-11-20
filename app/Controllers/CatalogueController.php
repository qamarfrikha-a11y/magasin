<?php
// Catalogue public : liste, recherche et filtre par catégorie
class CatalogueController extends Controller
{
    // GET index.php?controller=catalogue&action=index&q=souris&cat=2
    public function index(): void
    {
        $q   = trim((string) ($_GET['q'] ?? ''));
        $cat = (int) ($_GET['cat'] ?? 0);

        $this->render('catalogue/index', [
            'title'      => 'Catalogue',
            'produits'   => (new ProduitModel())->search($q, $cat),
            'categories' => (new CategorieModel())->findAll(),
            'q'          => $q,
            'cat'        => $cat,
        ]);
    }
}
