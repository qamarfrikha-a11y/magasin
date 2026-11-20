<?php
// Panier stocké en session : on ne garde que [code produit => quantité].
// Les prix sont relus en base à chaque affichage : on ne fait jamais confiance au client.
class Panier
{
    public static function contenu(): array
    {
        return $_SESSION['panier'] ?? [];
    }

    public static function ajouter(int $code, int $quantite = 1): void
    {
        $panier = self::contenu();
        $panier[$code] = ($panier[$code] ?? 0) + max(1, $quantite);
        $_SESSION['panier'] = $panier;
    }

    public static function modifier(int $code, int $quantite): void
    {
        $panier = self::contenu();
        if ($quantite <= 0) {
            unset($panier[$code]);
        } else {
            $panier[$code] = $quantite;
        }
        $_SESSION['panier'] = $panier;
    }

    public static function retirer(int $code): void
    {
        self::modifier($code, 0);
    }

    public static function vider(): void
    {
        unset($_SESSION['panier']);
    }

    public static function count(): int
    {
        return array_sum(self::contenu());
    }

    // Détail du panier avec les infos produit lues en base, et le total
    public static function detail(ProduitModel $produits): array
    {
        $lignes = [];
        $total = 0;
        foreach (self::contenu() as $code => $quantite) {
            $p = $produits->find((int) $code);
            if ($p === null) {
                self::retirer((int) $code);   // produit supprimé entre-temps
                continue;
            }
            $sousTotal = $p['prix'] * $quantite;
            $total += $sousTotal;
            $lignes[] = [
                'code' => $p['code'], 'designation' => $p['designation'], 'prix' => $p['prix'],
                'stock' => $p['qte'], 'quantite' => $quantite, 'sous_total' => $sousTotal,
            ];
        }
        return ['lignes' => $lignes, 'total' => $total];
    }
}
