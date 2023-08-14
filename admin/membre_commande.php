<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ' . URL . 'errors/error403.php');
}


$pageTitle = 'Espace Admin - Achats Commandes';
$pageMetaDesc = 'Retrouver la liste des commandes des membres';
$bodyId = ADMIN_LISTE_COMMANDE;

$id_membre = $_SESSION['user']['id_membre'];

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}

/* Affichage des commandes */

$url_page = "membre_commande.php?page=";
$result = pagination($bdd, "commande", $currentPage, 6);

$requestCommande = $bdd->prepare("SELECT c.id_commande, c.reference, c.total_ttc, c.etat, u.email, 
                          DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande' FROM commande c 
                          LEFT JOIN user u ON c.id_membre = u.id_membre   
                          LIMIT :firstArticle, :limite");

$requestCommande->bindValue(":firstArticle", $result['firstRow'], PDO::PARAM_INT);
$requestCommande->bindValue(":limite", $result['limit'], PDO::PARAM_INT);

try {
    $requestCommande->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$commandes = $requestCommande->fetchAll();

require_once('inc/header.inc.php');
?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-commande">

        <div class="table-list">

            <h3>Liste des commandes des clients</h3>
            <hr>

            <?php if (isset($_GET['send']) && ($_GET['send'] == "success")) : ?>

                <?php if (isset($_SESSION['content']['valid'])) : ?>
                    <div class='message-succes'> <?= $_SESSION['content']['valid']; ?></div>
                <?php endif;
                unset($_SESSION['content']['valid']); ?>

            <?php endif; ?>

            <?php if (isset($_GET['send']) && ($_GET['send'] == "error")) : ?>

                <?php if (isset($_SESSION['content']['error'])) : ?>
                    <div class='message-error'> <?= $_SESSION['content']['error']; ?></div>
                <?php endif;
                unset($_SESSION['content']['error']); ?>

            <?php endif; ?>

            <div class="search-link">
                <input class="inputForm" type="hidden" name="search" id="commande_search" value="commande_client">
                <input class="inputForm" type="hidden" name="search_id" id="id_search" value="<?= $id_membre; ?>">

                <select name="search-select" id="search-select">
                    <option value="all" selected>Toutes les commandes</option>
                    <option value="payé">Payé</option>
                    <option value="en cours de traitement">En cours de traitement</option>
                    <option value="envoyé">Envoyé</option>
                    <option value="livré">Livré</option>
                    <option value="annulé">Annulé</option>
                </select>

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
                        <th>Option</th>
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

                                    <a href="../facture-commande.php?commande=<?= $id_commande; ?>" title="Facture"><i class="fas fa-file-pdf"></i></a>

                                    <a href="gestion_commande.php?action=edit&commande=<?= $id_commande ?>" title="Modifier"><i class="fas fa-pencil"></i></a>

                                    <a class="btnDelete" href="gestion_commande.php?action=suppression&commande=<?= $id_commande ?>" title="Supprimer"> <i class="fa fa-trash-can"></i></a>

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

        <?php require_once('../inc/pagination.inc.php'); ?>

    </section>

</section>
</div>

<?php require_once('inc/footer.inc.php');  ?>