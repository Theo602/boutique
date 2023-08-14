<?php

require_once('../init.php');

$data = json_decode(file_get_contents('php://input'), true);

$sqlAdmin = "SELECT c.id_commande, c.reference, c.total_ttc, c.etat, u.email, 
        DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande' FROM commande c 
        LEFT JOIN user u ON c.id_membre = u.id_membre";

$sqlUser = "SELECT c.id_commande, c.reference, c.total_ttc, c.etat, u.email, 
             DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande' FROM commande c 
             INNER JOIN user u ON c.id_membre = u.id_membre 
             AND c.id_membre = :id_membre";


if ($data["search"] === 'commande_dashboard') {

    if ($data["status"] === 'all') {
        $sqlAdmin = $sqlAdmin . " ORDER BY id_commande LIMIT 0, 6";
        $request = $bdd->prepare($sqlAdmin);
    } else {
        $sqlAdmin = $sqlAdmin . " WHERE c.etat = :etat ORDER BY id_commande  DESC LIMIT 0, 6";
        $request = $bdd->prepare($sqlAdmin);
        $request->bindParam(":etat", $data["status"], PDO::PARAM_STR);
    }
}

if ($data["search"] === 'commande_client') {

    if ($data["status"] === 'all') {
        $sqlAdmin = $sqlAdmin;
        $request = $bdd->prepare($sqlAdmin);
    } else {
        $sqlAdmin = $sqlAdmin . " WHERE c.etat = :etat";
        $request = $bdd->prepare($sqlAdmin);
        $request->bindParam(":etat", $data["status"], PDO::PARAM_STR);
    }
}

if ($data["search"] === 'commande_compte') {

    if ($data["status"] === 'all') {
        $sqlUser = $sqlUser . " ORDER BY id_commande  DESC LIMIT 0, 6";
        $request = $bdd->prepare($sqlUser);
    } else {
        $sqlUser = $sqlUser . " WHERE c.etat = :etat ORDER BY id_commande  DESC LIMIT 0, 6";
        $request = $bdd->prepare($sqlUser);
        $request->bindParam(":etat", $data["status"], PDO::PARAM_STR);
    }
    $request->bindParam(":id_membre", $data["user"], PDO::PARAM_INT);
}

if ($data["search"] === 'commande_user') {

    if ($data["status"] === 'all') {
        $sqlUser = $sqlUser . " ORDER BY id_commande  DESC";
        $request = $bdd->prepare($sqlUser);
    } else {
        $sqlUser = $sqlUser . " WHERE c.etat = :etat ORDER BY id_commande  DESC";
        $request = $bdd->prepare($sqlUser);
        $request->bindParam(":etat", $data["status"], PDO::PARAM_STR);
    }
    $request->bindParam(":id_membre", $data["user"], PDO::PARAM_INT);
}

$request->execute();
$commandes = $request->fetchAll();

require('liste-ajax-commande.inc.php');
