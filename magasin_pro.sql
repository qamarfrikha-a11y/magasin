-- Base du projet Magasin Pro (extension de magasin2)
-- Import : phpMyAdmin > Importer, ou : mysql -u root < magasin_pro.sql
SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS magasin_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE magasin_pro;

DROP TABLE IF EXISTS ligne_commande;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS categorie;

CREATE TABLE categorie (
    code INT AUTO_INCREMENT PRIMARY KEY,
    nom  VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE produit (
    code           INT AUTO_INCREMENT PRIMARY KEY,
    designation    VARCHAR(250) NOT NULL,
    prix           INT NOT NULL,
    qte            INT NOT NULL,
    code_categorie INT NOT NULL,
    CONSTRAINT fk_produit_categorie FOREIGN KEY (code_categorie) REFERENCES categorie(code)
) ENGINE=InnoDB;

CREATE TABLE utilisateur (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nom          VARCHAR(100) NOT NULL,
    email        VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role         ENUM('admin','client') NOT NULL DEFAULT 'client'
) ENGINE=InnoDB;

CREATE TABLE commande (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur   INT NOT NULL,
    date_commande    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total            INT NOT NULL DEFAULT 0,
    statut           ENUM('validee','expediee') NOT NULL DEFAULT 'validee',
    CONSTRAINT fk_cmd_user FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
) ENGINE=InnoDB;

CREATE TABLE ligne_commande (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    id_commande   INT NOT NULL,
    code_produit  INT NOT NULL,
    quantite      INT NOT NULL,
    prix_unitaire INT NOT NULL,
    CONSTRAINT fk_ligne_cmd  FOREIGN KEY (id_commande)  REFERENCES commande(id) ON DELETE CASCADE,
    CONSTRAINT fk_ligne_prod FOREIGN KEY (code_produit) REFERENCES produit(code)
) ENGINE=InnoDB;

INSERT INTO categorie (code, nom) VALUES
    (1,'Matériel Informatique'),(2,'Accessoires'),(3,'Meuble Bureau'),(4,'Informatique');

INSERT INTO produit (code, designation, prix, qte, code_categorie) VALUES
    (24,'clavier',60,12,1),(25,'micro',12,100,2),(26,'souris',10,15,2),
    (27,'chaise',60,5,3),(28,'bureau',200,5,3),(40,'Souris gamer',15,10,4);

-- Comptes de démonstration : admin@magasin.tn / admin123  et  client@magasin.tn / client123
INSERT INTO utilisateur (nom, email, mot_de_passe, role) VALUES
    ('Administrateur','admin@magasin.tn','$2y$10$SEwLa4Bdb571YMaSQWZ.zeO/EtHISDL73SOpJsVfVKJPvD04I36Eq','admin'),
    ('Client Démo','client@magasin.tn','$2y$10$TWjd0wL2N5CWWzWAseU4A.whldwNdmNZ4fpaQ28igpWzn2fc8bkda','client');
