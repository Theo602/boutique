<?php

require_once('config/init.php');

// Destruction de la session

unset($_SESSION['user']);
session_destroy();

// Redirection sur connexion.php

header('Location: index.php');
exit();
