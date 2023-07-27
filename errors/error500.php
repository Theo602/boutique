<?php

require_once('../config/init.php');

$pageTitle = 'Erreur 500 - Internal Server Error';
$pageMetaDesc = 'Le serveur a rencontré un problème inattendu';
$bodyId = PAGE_500;

require_once('inc/error/header_erreur.inc.php');

?>

<section class="section-1-error">

    <h2>Le serveur a rencontré un problème inattendu</h2>
    <figure class="figure-pageError">
        <img class="img-pageError" src="../asset/img/error/error500.jpg" alt="Erreur 500 - Internal Server Error">
    </figure>

    <div class="container-pageError">
        <p class="text-pageError">500 Internal Server Error</p>
        <a class="btn-pageError" href="../index.php">Retour à l'accueil</a>
    </div>

</section>

<?php require_once('inc/error/footer_error.inc.php'); ?>