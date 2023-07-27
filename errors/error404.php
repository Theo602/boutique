<?php

require_once('../config/init.php');

$pageTitle = "Erreur 404 - Page not found";
$pageMetaDesc = "La page demandée est introuvable ou n'existe pas";
$bodyId = PAGE_404;

require_once('inc/error/header_erreur.inc.php');

?>

<section class="section-1-error">

    <h2>La page demandée est introuvable ou n'existe pas</h1>
        <figure class="figure-pageError">
            <img class="img-pageError" src="../asset/img/error/error404.jpg" alt="Erreur 404 - Page not found">
        </figure>

        <div class="container-pageError">
            <p class="text-pageError">404 Page non trouvée</p>
            <a class="btn-pageError" href="../index.php">Retour à l'accueil</a>
        </div>

</section>

<?php require_once('inc/error/footer_error.inc.php'); ?>