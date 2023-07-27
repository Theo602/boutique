<?php

define('URL', 'http://localhost/boutique/');
define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/boutique/');

require_once('database.php');
require_once('function/function.inc.php');
require_once('constante.inc.php');

// on se connecte à la base de donnée

$bdd = getConnexion();

//Ouverture de la session

session_start();
