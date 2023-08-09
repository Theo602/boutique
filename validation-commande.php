<?php

require_once('config/init.php');


$pageTitle = 'Validation de la commande';
$pageMetaDesc = 'Votre commande est validé. Vous serez livré très bienôt';
$bodyId = PROCESS_COMMANDE;


/* Redirection si le membre n'est pas connecté */

if (!userConnected()) {
    header('Location: ' . URL . 'panier.php');
    exit();
}


$id_commande = $_SESSION['commande']['id_commande'];

$requestCommande = $bdd->prepare('SELECT * FROM commande WHERE id_commande = :id_commande ');
$requestCommande->bindParam(":id_commande", $id_commande, PDO::PARAM_INT);

try {
    $requestCommande->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$commande = $requestCommande->fetch(PDO::FETCH_ASSOC);
extract($commande);

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-achat">

    <h2>Confirmation de la commande</h2>
    <hr>
    <p>

        Validation de commande, le paiement a été accepté.<br>
        Attention! Nous expédions les commandes en une seule fois,<br> au moment où l’article
        dont la date de sortie est la plus tardive est disponible.

    </p>

</section>

<section class="section-2-achat">

    <div class="information-achat">

        <h3>Confirmation de votre commande</h3>

        <hr>

        <div class="resume-achat">


            <p>Nous vous remercions de votre commande n° <b><?= $reference; ?></b>.</p>
            <p>Notre équipe va maintenant préparer et expédier, le plus rapidement possible, vos articles à l'adresse choisie.</p>
            <hr>

            <p>Vous recevrez dans quelques instant un email de confirmation contenant toutes les informations nécessaires<br>
                au suivi de votre commande.
            <p>
            <p>Nous vous remercions pour votre confiance</p>

            <hr>

            <?php if (userIsAdmin()) : ?>

                <p>Pour suivre votre commande, rendez-vous sur votre <a class="btn-compte" href="admin/admin_compte.php">compte</a>.</p>

            <?php else : ?>

                <p>Pour suivre votre commande, rendez-vous sur votre <a class="btn-compte" href="compte.php">compte</a>.</p>

            <?php endif ?>

        </div>

    </div>



</section>

<?php require_once('inc/footer.inc.php');  ?>