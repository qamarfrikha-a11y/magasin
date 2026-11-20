<?php
class LigneCommandeModel extends Model
{
    protected string $table = 'ligne_commande';
    protected string $primaryKey = 'id';

    public function findByCommande(int $idCommande): array
    {
        $sql = "SELECT l.quantite, l.prix_unitaire, p.designation
                FROM ligne_commande l JOIN produit p ON p.code = l.code_produit
                WHERE l.id_commande = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idCommande]);
        return $stmt->fetchAll();
    }
}
