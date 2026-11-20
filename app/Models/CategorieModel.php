<?php
class CategorieModel extends Model
{
    protected string $table = 'categorie';
    protected string $primaryKey = 'code';

    // Une catégorie est-elle encore utilisée par des produits ?
    public function nbProduits(int $code): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM produit WHERE code_categorie = :c');
        $stmt->execute(['c' => $code]);
        return (int) $stmt->fetchColumn();
    }
}
