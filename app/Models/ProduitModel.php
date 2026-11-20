<?php
class ProduitModel extends Model
{
    protected string $table = 'produit';
    protected string $primaryKey = 'code';

    // Produits avec le nom de leur catégorie (administration)
    public function findAllWithCategorie(): array
    {
        $sql = "SELECT p.code, p.designation, p.prix, p.qte, c.nom AS categorie
                FROM produit p LEFT JOIN categorie c ON p.code_categorie = c.code
                ORDER BY p.code";
        return $this->db->query($sql)->fetchAll();
    }

    // Catalogue public : recherche par mot-clé et filtre par catégorie (requête préparée)
    public function search(string $q = '', int $categorie = 0): array
    {
        $sql = "SELECT p.code, p.designation, p.prix, p.qte, c.nom AS categorie
                FROM produit p LEFT JOIN categorie c ON p.code_categorie = c.code
                WHERE p.designation LIKE :q";
        $params = ['q' => '%' . $q . '%'];
        if ($categorie > 0) {
            $sql .= " AND p.code_categorie = :cat";
            $params['cat'] = $categorie;
        }
        $sql .= " ORDER BY p.designation";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Produits dont le stock est inférieur ou égal au seuil
    public function stockFaible(int $seuil): array
    {
        $stmt = $this->db->prepare("SELECT code, designation, qte FROM produit WHERE qte <= :s ORDER BY qte, designation");
        $stmt->execute(['s' => $seuil]);
        return $stmt->fetchAll();
    }

    // Les produits les plus vendus
    public function topVendus(int $limite = 5): array
    {
        $sql = "SELECT p.designation, SUM(l.quantite) AS vendus
                FROM ligne_commande l JOIN produit p ON p.code = l.code_produit
                GROUP BY p.code, p.designation
                ORDER BY vendus DESC LIMIT " . (int) $limite;
        return $this->db->query($sql)->fetchAll();
    }
}
