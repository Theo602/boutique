<?php

require_once('../init.php');

$data = json_decode(file_get_contents('php://input'), true);

$sqlProduit = "SELECT * FROM produit";

if ($data["search"] === 'produit_boutique') {

    if ($data["categorie"] === 'all') {
        $sqlProduit = $sqlProduit;
        $request = $bdd->prepare($sqlProduit);
    } else {
        $sqlProduit = $sqlProduit . " WHERE categorie = :categorie";
        $request = $bdd->prepare($sqlProduit);
        $request->bindParam(':categorie', $data["categorie"], PDO::PARAM_STR);
    }
}

$request->execute();
$produits = $request->fetchAll();

require('liste-ajax-produit.inc.php');
