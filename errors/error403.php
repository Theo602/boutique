<?php

require_once('../config/init.php');

$pageTitle = "Erreur 403 - Acces Denied";
$pageMetaDesc = "Vous n'avez pas les droits nécessaires pour afficher cette page";
$bodyId = PAGE_403;

require_once('inc/error/header_erreur.inc.php');

?>

<section class="section-1-error">

    <h2>Vous n'avez pas les droits nécessaires pour afficher cette page</h1>
        <figure class="figure-pageError">
            <img class="img-pageError" src="../asset/img/error/error403.jpg" alt="Erreur 403 - Acces Denied">
        </figure>

        <div class="container-pageError">
            <p class="text-pageError">403 Accès non autorisé</p>
            <a class="btn-pageError" href="../index.php">Retour à l'accueil</a>
        </div>

</section>

<?php require_once('inc/error/footer_error.inc.php'); ?>