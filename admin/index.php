<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ' . URL . 'errors/error403.php');
}


$pageTitle = 'Espace Admin - Dashboard';
$pageMetaDesc = "Bienvenue sur votre panneau d'administration";
$bodyId = ADMIN_DASHBOARD;


$id_membre = $_SESSION['user']['id_membre'];

/* Nombre : produit - commande - membre*/

$countProduit = countDashboard($bdd, "produit");
$countCommande = countDashboard($bdd, "commande");
$countUser = countDashboard($bdd, "user");

/* Affichage des commandes */

$requestDetailCommande = $bdd->prepare("SELECT c.id_commande, c.reference, c.total_ttc, c.etat, u.email, 
                          DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande' FROM commande c 
                          LEFT JOIN user u ON c.id_membre = u.id_membre 
                          ORDER BY id_commande  DESC LIMIT 0, 6");

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

            <div class="search-link">
                <input class="inputForm" type="hidden" name="search" id="commande_search" value="commande_dashboard">
                <input class="inputForm" type="hidden" name="search_id" id="id_search" value="<?= $id_membre; ?>">

                <select name="search-select" id="search-select">
                    <option value="all" selected>Toutes les commandes</option>
                    <option value="payé">Payé</option>
                    <option value="en cours de traitement">En cours de traitement</option>
                    <option value="envoyé">Envoyé</option>
                    <option value="livré">Livré</option>
                    <option value="annulé">Annulé</option>
                </select>

                <p>
                    <a class="table-link" href="membre_commande.php">Voir tout</a>
                </p>
            </div>

            <table>

                <thead>
                    <tr class="table-top-commande">
                        <th>Commande nᵒ</th>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>TotalTcc</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>

                <tbody class="search">

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
                                <td data-label="TotalTcc"><?= $total_ttc; ?></td>

                                <td data-label="Status">
                                    <p class="table-etat <?php
                                                            if ($etat === 'livré') {
                                                                echo 'etat-livrer';
                                                            } elseif ($etat === 'envoyé') {
                                                                echo 'etat-envoyer';
                                                            } elseif ($etat === 'en cours de traitement') {
                                                                echo 'etat-pending';
                                                            } elseif ($etat === 'annulé') {
                                                                echo 'etat-annuler';
                                                            } else {
                                                                echo 'etat-payer';
                                                            }
                                                            ?>">
                                        <?= ucfirst($etat); ?>
                                    </p>
                                </td>

                                <td data-label="Option">

                                    <a href="membre_detail_commande.php?commande=<?= $id_commande; ?>" title="Voir"><i class="fa fa-eye"></i></a>

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