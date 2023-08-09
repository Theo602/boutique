<?php

require_once('config/init.php');


$pageTitle = 'Déconnexion';
$pageMetaDesc = 'Déconnectez vous de votre compte';
$bodyId = DECONNECTE;


// Destruction de la session

unset($_SESSION['user']);
session_destroy();

// Redirection sur connexion.php

header('Location: ' . URL);
exit();
