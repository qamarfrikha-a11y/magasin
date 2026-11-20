<?php
class CommandeModel extends Model
{
    protected string $table = 'commande';
    protected string $primaryKey = 'id';

    /**
     * Valide une commande dans UNE transaction :
     * commande + lignes + décrément du stock réussissent ensemble, ou rien n'est enregistré.
     * @param array $panier [code produit => quantité]
     * @throws RuntimeException si le stock est insuffisant
     */
    public function passer(int $idUtilisateur, array $panier): int
    {
        $lignes = new LigneCommandeModel();   // partage la même connexion PDO (Singleton)

        $this->db->beginTransaction();
        try {
            $idCommande = $this->create(['id_utilisateur' => $idUtilisateur, 'total' => 0]);
            $total = 0;

            foreach ($panier as $code => $quantite) {
                // FOR UPDATE verrouille la ligne : deux clients ne peuvent pas acheter le dernier article
                $stmt = $this->db->prepare('SELECT designation, prix, qte FROM produit WHERE code = :c FOR UPDATE');
                $stmt->execute(['c' => $code]);
                $p = $stmt->fetch();

                if ($p === false) {
                    throw new RuntimeException("Un produit de votre panier n'existe plus.");
                }
                if ($p['qte'] < $quantite) {
                    throw new RuntimeException("Stock insuffisant pour « {$p['designation']} » (disponible : {$p['qte']}).");
                }

                $maj = $this->db->prepare('UPDATE produit SET qte = qte - :q WHERE code = :c');
                $maj->execute(['q' => $quantite, 'c' => $code]);

                // Le prix vient de la base, jamais du navigateur
                $lignes->create([
                    'id_commande' => $idCommande, 'code_produit' => $code,
                    'quantite' => $quantite, 'prix_unitaire' => $p['prix'],
                ]);
                $total += $p['prix'] * $quantite;
            }

            $this->update($idCommande, ['total' => $total]);
            $this->db->commit();
            return $idCommande;
        } catch (Throwable $e) {
            $this->db->rollBack();   // rien n'est enregistré
            throw $e;
        }
    }

    public function findAllWithClient(): array
    {
        $sql = "SELECT c.id, c.date_commande, c.total, c.statut, u.nom AS client, u.email
                FROM commande c JOIN utilisateur u ON u.id = c.id_utilisateur
                ORDER BY c.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findByUtilisateur(int $idUtilisateur): array
    {
        $stmt = $this->db->prepare('SELECT * FROM commande WHERE id_utilisateur = :u ORDER BY id DESC');
        $stmt->execute(['u' => $idUtilisateur]);
        return $stmt->fetchAll();
    }

    // --- Statistiques du tableau de bord ---
    public function chiffreAffaires(): int
    {
        return (int) $this->db->query('SELECT COALESCE(SUM(total), 0) FROM commande')->fetchColumn();
    }

    public function nbCommandes(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM commande')->fetchColumn();
    }

    public function caParJour(int $jours = 14): array
    {
        $sql = "SELECT DATE(date_commande) AS jour, SUM(total) AS ca
                FROM commande
                WHERE date_commande >= DATE_SUB(CURDATE(), INTERVAL " . (int) $jours . " DAY)
                GROUP BY DATE(date_commande) ORDER BY jour";
        return $this->db->query($sql)->fetchAll();
    }
}
