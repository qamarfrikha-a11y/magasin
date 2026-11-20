<?php
// Paramètres de l'application : le SEUL fichier à modifier pour changer de serveur.
class Config
{
    const DB_HOST    = 'localhost';
    const DB_NAME    = 'magasin_pro';
    const DB_USER    = 'root';
    const DB_PASS    = '';
    const DB_CHARSET = 'utf8mb4';

    const STOCK_FAIBLE = 5;   // seuil du tableau de bord (produits presque en rupture)
}
