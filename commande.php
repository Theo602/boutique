<?php

require_once('config/init.php');

$pageTitle = 'Espace Membre - Commande';
$pageMetaDesc = 'Retrouver la liste de vos commandes.';
$bodyId = MEMBER_COMMANDE;


if (userIsAdmin()) {
    header('Location: admin');
}

if (!userConnected()) {
    header('Location: errors/error403.php');
    exit();
}


/* Affichage des commandes */

$id_membre = $_SESSION['user']['id_membre'];

$requestCommande = $bdd->prepare("SELECT *, DATE_FORMAT(c.created_at, '%d/%m/%Y') AS created_at FROM commande c INNER JOIN user u ON 
    c.id_membre = u.id_membre AND u.id_membre = :id_membre");
$requestCommande->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);

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

<div class="container-profil">

    <section class="section-profil-left">

        <?php require_once('inc/menu-profil.inc.php');  ?>

    </section>

    <section class="section-profil-right">

        <section class="section-1-commande-user">

            <div class="table-list">

                <h3>Liste des commandes</h3>
                <hr>

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

                                    <td>Commande nᵒ <?= $id_produit; ?></td>
                                    <td><i class="fas fa-chevron-down"></td>

                                </tr>

                                <tr class="table-details-commande">
                                    <td data-label="Commande nᵒ"><?= $id_commande; ?></td>
                                    <td data-label="Référence"><?= $reference; ?></td>
                                    <td data-label="Client"><?= $email; ?></td>
                                    <td data-label="Date"><?= $created_at; ?></td>
                                    <td data-label="TotalTcc"><?= $total_ttc; ?></td>
                                    <td data-label="Status"><?= $etat; ?></td>
                                    <td data-label="Option">

                                        <a href="" title="Voir"><i class="fa fa-eye"></i></a>

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