<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ../errors/error403.php');
}


/* Affichage du détail de la commande  */

if (isset($_GET['commande']) && !empty($_GET['commande'])) {

    /* Affichage des informations de la commande */

    $request = $bdd->prepare("SELECT DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande',
                                     DATE_FORMAT(c.update_at, '%d/%m/%Y') AS 'date_update',
                                     c.reference AS 'commande_reference' , c.livraison, c.adresse_livraison,
                                     c.total_ht, c.total_ttc, c.etat, c.id_membre, dc.id_commande, c.id_commande AS 'commande_id'
                                     FROM detail_commande dc
                                     INNER JOIN commande c ON  dc.id_commande = c.id_commande
                                     AND dc.id_commande = :id_commande");

    $request->bindParam(":id_commande", $_GET['commande'], PDO::PARAM_INT);

    try {
        $request->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    if ($request->rowCount() ==  0) {
        header('Location: errors/error404.php');
        exit();
    }

    $information = $request->fetch(PDO::FETCH_ASSOC);
    extract($information);


    /* Affichage du détail des produits de la commande */

    $query = $bdd->prepare("SELECT dc.id_produit, dc.prix AS 'detail_prix', dc.quantite AS 'detail_quantite',
                                            dc.total AS 'detail_total', p.id_produit, p.reference, p.titre, p.photo
                                            FROM detail_commande dc
                                            INNER JOIN produit p  ON dc.id_produit = p.id_produit
                                            AND dc.id_commande = :id_commande");

    $query->bindParam(":id_commande", $_GET['commande'], PDO::PARAM_INT);

    try {
        $query->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    if ($query->rowCount() ==  0) {
        header('Location: errors/error404.php');
        exit();
    }
} else {
    header('Location: errors/error404.php');
    exit();
}


$pageTitle = 'Espace Admin - Commande membre n° ' . $commande_reference;
$pageMetaDesc = 'Retrouver le détail de votre commande.';
$bodyId = ADMIN_COMMANDE_MEMBER;

require_once('inc/header.inc.php');

?>

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-client">

        <div class="dashboard-info">

            <h3>Commande</h3>
            <hr>

            <div class="order-client">

                <a href="" class="btn-commande"></i>Bon de commande</a>
                <a href="gestion_commande.php?action=edit&commande=<?= $commande_id ?>" title="Changer le status" class="btn-commande"></i>Modifier la commande</a>
                <a href="membre_commande.php" title="retour sur les commandes clients" class="btn-commande"></i>Retour sur les commandes clients</a>

            </div>

        </div>

    </section>

    <section class="section-2-client">

        <div class="background-commande">

            <div class="information-client">

                <h3>Commande client n° <?= $commande_reference ?></h3>

                <hr>

                <div class="resume-commande">

                    <div class="fiche-information">

                        <h3>Information</h3>
                        <hr>

                        <p>Commande passée le <?= $date_commande; ?></p>
                        <p>Commande mis à jour le <?= $date_update; ?></p>
                        <p>Référence : <?= $commande_reference ?></p>
                        <p>Status : <?= $etat ?></p>

                    </div>

                    <div class="fiche-information">

                        <h3>Adresse de livraison</h3>
                        <hr>

                        <p><?= $adresse_livraison; ?></p>

                    </div>

                    <div class="fiche-information">

                        <h3>Adresse de facturation</h3>
                        <hr>

                        <p><?= $adresse_livraison; ?></p>

                    </div>

                    <div class="fiche-information">

                        <h3>Mode de livraison</h3>
                        <hr>

                        <p><?= $livraison; ?></p>

                    </div>

                </div>


            </div>

            <div class="list-client">

                <h3>Vos produits</h3>
                <hr>

                <table>

                    <thead>

                        <tr class="table-top-client">

                            <th>Photo</th>
                            <th>Référence</th>
                            <th>Nom du produit</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Total</th>

                        </tr>

                    </thead>
                    <tbody>

                        <?php while ($detail = $query->fetch(PDO::FETCH_ASSOC)) : extract($detail); ?>

                            <tr class="table-responsive">

                                <td>Produit : <?= ucfirst($titre); ?></td>
                                <td><i class="fas fa-chevron-down"></td>

                            </tr>

                            <tr class="table-details-client">

                                <td data-label="Photo">

                                    <figure>
                                        <a href="fiche-produit.php?id_produit=<?= $id_produit; ?>" title="Voir le produit">
                                            <img src="<?= $photo; ?>"></a>
                                    </figure>

                                </td>

                                <td data-label="Référence"><?= $reference; ?></td>
                                <td data-label="Nom du produit"><?= ucfirst($titre); ?></td>
                                <td data-label="Quantité"><?= $detail_quantite; ?></td>
                                <td data-label="Prix"><?= $detail_prix; ?> €</td>
                                <td data-label="Total"><?= $detail_total; ?> €</td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

                <p class="panier-total">Total HT : <?= $total_ht; ?>€</p>
                <p class="panier-total">Total TVA (20%) : <?= tauxTva($total_ht); ?>€</p>
                <p class="panier-total">Total TTC : <?= $total_ttc; ?>€</p>

                <div class="order">
                    <a class="btn-order" href="">Télécharger la facture</a>
                    <a class="btn-order" href="membre_commande.php">Retour sur les commandes clients</a>
                </div>

            </div>

        </div>

    </section>

</section>






<?php require_once('inc/footer.inc.php');  ?>