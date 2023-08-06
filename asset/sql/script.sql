-- Creation de la base de données:

CREATE DATABASE IF NOT EXISTS boutique CHARACTER SET = 'utf8mb4' COLLATE = 'utf8mb4_general_ci';

-- Utiliser la base de données:
 
USE boutique

-- Creation des tables de la base de donnée:

-- Table user 

CREATE TABLE user(

    id_membre INT(11) NOT NULL AUTO_INCREMENT,
    prenom VARCHAR(60) NOT NULL,
    nom VARCHAR(60) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    civilite VARCHAR(60) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    code_postal INT(5) UNSIGNED ZEROFILL NOT NULL,
    adresse VARCHAR(100) NOT NULL,
    status INT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_membre)

) ENGINE=InnoDB;

-- Table produit 

CREATE TABLE produit(

    id_produit INT(11) NOT NULL AUTO_INCREMENT,
    reference VARCHAR(60) NOT NULL,
    categorie VARCHAR(60) NOT NULL,
    titre VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    couleur VARCHAR(60) NOT NULL,
    taille VARCHAR(5) NOT NULL,
    public ENUM('homme','femme','mixte') NOT NULL,
    photo VARCHAR(255) NOT NULL,
    prix DOUBLE NOT NULL,
    stock INT(11) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_produit)

) ENGINE=InnoDB;

-- Table commande 

CREATE TABLE commande(

    id_commande INT(11) NOT NULL AUTO_INCREMENT,
    id_membre INT(11) DEFAULT NULL,
    reference VARCHAR(255) NOT NULL,
    livraison VARCHAR(100) NOT NULL,
    adresse_livraison VARCHAR(255) NOT NULL,
    total_ht DOUBLE NOT NULL,
    total_ttc DOUBLE NOT NULL,
    etat enum('payé','en cours de traitement','envoyé','livré') NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id_commande),
    FOREIGN KEY(id_membre) REFERENCES user(id_membre) ON DELETE SET NULL ON UPDATE CASCADE

) ENGINE=InnoDB;

-- Table detail_commande 

CREATE TABLE detail_commande(

    id_detail_commande INT(11) NOT NULL AUTO_INCREMENT,
    id_commande INT(11) NOT NULL,
    id_produit INT(11) DEFAULT NULL,
    quantite INT(11) NOT NULL,
    prix DOUBLE NOT NULL,
    total DOUBLE NOT NULL,

    PRIMARY KEY(id_detail_commande),

    FOREIGN KEY(id_commande) REFERENCES commande(id_commande) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY(id_produit) REFERENCES produit(id_produit) ON DELETE SET NULL ON UPDATE RESTRICT

) ENGINE=InnoDB;