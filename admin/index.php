<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ' . URL . 'errors/error403.php');
}


$pageTitle = 'Espace Admin - Dashboard';
$pageMetaDesc = "Bienvenue sur votre panneau d'administration";
$bodyId = ADMIN_DASHBOARD;


/* Nombre de produit */

$requestProduit = $bdd->prepare('SELECT COUNT(*) FROM produit');

try {
    $requestProduit->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$countProduit = $requestProduit->fetchColumn();

/* Nombre de commande */

$requestCommande = $bdd->prepare('SELECT COUNT(*) FROM commande');

try {
    $requestCommande->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$countCommande = $requestCommande->fetchColumn();

/* Nombre de membre */

$requestUser = $bdd->prepare('SELECT COUNT(*) FROM user');

try {
    $requestUser->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$countUser = $requestUser->fetchColumn();

/* Affichage des commandes */

$requestDetailCommande = $bdd->prepare("SELECT *, DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande' FROM commande c LEFT JOIN user u ON 
c.id_membre = u.id_membre WHERE c.etat = 'payé' ORDER BY id_commande  DESC LIMIT 0, 6");

try {
    $requestDetailCommande->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$commandes = $requestDetailCommande->fetchAll();

require_once('inc/header.inc.php');
?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-dashboard">

        <div class="block-indication">

            <div class="fiche-indication">

                <h3>Produits</h3>
                <p><?= $countProduit; ?></p>

            </div>

            <div class="fiche-indication">

                <h3>Commandes</h3>
                <p><?= $countCommande; ?></p>

            </div>

            <div class="fiche-indication">

                <h3>Membres</h3>
                <p><?= $countUser; ?></p>

            </div>

        </div>

    </section>

    <section class="section-2-dashboard">

        <div class="table-list">

            <h3>Commandes récentes des clients</h3>
            <hr>

            <p>
                <a class="table-link" href="membre_commande.php">Voir tout</a>
            </p>

            <table>

                <thead>
                    <tr class="table-top-commande">
                        <th>Commande nᵒ</th>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($commandes)) : ?>

                        <?php foreach ($commandes as $commande) : extract($commande); ?>

                            <tr class="table-responsive">

                                <td>Commande nᵒ <?= $id_commande; ?></td>
                                <td><i class="fas fa-chevron-down"></td>

                            </tr>

                            <tr class="table-details-commande">
                                <td data-label="Commande nᵒ"><?= $id_commande; ?></td>
                                <td data-label="Référence"><?= $reference; ?></td>
                                <td data-label="Client"><?= ($email) ? $email : 'Commande archivée' ?></td>
                                <td data-label="Date"><?= $date_commande; ?></td>
                                <td data-label="Total"><?= $total_ttc; ?></td>
                                <td data-label="Status"><?= $etat; ?></td>
                                <td data-label="Option">

                                    <a href="membre_commande.php" title="Voir"><i class="fa fa-eye"></i></a>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr class="td-empty">
                            <td colspan="7">Aucune commande</td>
                        </tr>

                    <?php endif ?>

                </tbody>
            </table>

        </div>

    </section>

</section>


<?php require_once('inc/footer.inc.php');  ?>