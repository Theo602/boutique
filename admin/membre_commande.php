<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ../errors/error403.php');
}


$pageTitle = 'Espace Admin - Achats Commandes';
$pageMetaDesc = 'Retrouver la liste des commandes des membres';
$bodyId = ADMIN_LISTE_COMMANDE;


/* Affichage des commandes */

$requestCommande = $bdd->prepare("SELECT *, DATE_FORMAT(c.created_at, '%d/%m/%Y') AS created_at FROM commande c LEFT JOIN user u ON 
c.id_membre = u.id_membre ");

try {
    $requestCommande->execute();
} catch (PDOException $exception) {
    header('Location: ../errors/error500.php');
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
                                <td data-label="Date"><?= $created_at; ?></td>
                                <td data-label="TotalTcc"><?= $total_ttc; ?></td>
                                <td data-label="Status"><?= $etat; ?></td>
                                <td data-label="Option">

                                    <a href="membre_detail_commande.php?commande=<?= $id_commande; ?>" title="Voir"><i class="fa fa-eye"></i></a>

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

    </section>

</section>
</div>

<?php require_once('inc/footer.inc.php');  ?>