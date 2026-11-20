# Magasin Pro – Projet MVC PHP

## Installation (WAMP)
1. Copier `magasin_pro/` dans `C:\wamp64\www\`
2. phpMyAdmin > Importer > `magasin_pro.sql` (crée la base, les tables et les données)
3. Ouvrir http://localhost/magasin_pro/public/

Comptes de démonstration : `admin@magasin.tn / admin123` — `client@magasin.tn / client123`

## Architecture
- `public/index.php` : contrôleur frontal (liste blanche + contrôle CSRF sur tout POST)
- `app/Controllers/` : Controller (abstrait), Catalogue, Produit, Categorie, Panier, Commande, Auth, Admin, Api
- `app/Models/` : Model (CRUD générique) + une classe fille par table (3 lignes de config)
- `app/Services/` : Panier (session), Auth (session + rôles), Csrf
- `app/Views/` : uniquement de l'affichage ; toute donnée passe par `e()`

## Niveaux
| Niveau | Contenu |
|---|---|
| 1 | CRUD produits et catégories en MVC, autoloading |
| 2 | Catalogue + recherche, panier en session, commande en transaction PDO (`CommandeModel::passer`) |
| 3 | Authentification, rôles admin/client, tableau de bord (CA, top 5, stock faible, Chart.js) |
| 4 | API JSON + recherche en direct (`fetch`), jeton CSRF, export CSV |

## Démonstrations « waouh »
1. **Transaction** : mettre 9 chaises dans le panier (stock 5) puis valider -> rien n'est enregistré.
2. **XSS** : saisir `<script>alert(1)</script>` comme désignation ; retirer `e()` dans une vue pour voir l'attaque.
3. **Injection SQL** : essayer `' OR 1=1 --` dans la recherche ; les requêtes préparées l'ignorent.
4. **Accès direct** : ouvrir `app/Views/layouts/header.php` dans le navigateur -> impossible.
5. **CSRF** : retirer `csrf_field()` d'un formulaire -> erreur 419.

## Exercices pour les étudiants (à retirer de la correction)
- Ajouter la gestion d'image produit (upload sécurisé).
- URL propres avec `.htaccess` (`/produit/form/24`).
- Annulation de commande avec remise en stock (dans une transaction).
