<?php
// API JSON : GET ?controller=api&action=produits&q=sou&cat=2
class ApiController extends Controller
{
    public function produits(): void
    {
        $q   = trim((string) ($_GET['q'] ?? ''));
        $cat = (int) ($_GET['cat'] ?? 0);
        $this->json((new ProduitModel())->search($q, $cat));
    }
}
