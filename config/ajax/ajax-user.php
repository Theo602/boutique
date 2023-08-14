<?php

require_once('../init.php');

$data = json_decode(file_get_contents('php://input'), true);

$sqlUser = "SELECT id_membre, prenom, nom, email, status FROM user";

if ($data["search"] === 'role_user') {

    if ($data["status"] === 'all') {
        $sqlUser = $sqlUser;
        $request = $bdd->prepare($sqlUser);
    } else {
        $sqlUser = $sqlUser . " WHERE status = :status";
        $request = $bdd->prepare($sqlUser);
        $request->bindParam(':status', $data["status"], PDO::PARAM_STR);
    }
}

$request->execute();
$users = $request->fetchAll();

require('liste-ajax-user.inc.php');
