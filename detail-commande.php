<?php

require_once('config/init.php');


if (!userConnected()) {
    header('Location: ' . URL . 'errors/error403.php');
    exit();
}


/* Affichage du détail de la commande  */

if (isset($_GET['commande']) && !empty($_GET['commande'])) {

    /* Affichage des informations de la commande */

    $request = $bdd->prepare("SELECT DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande', 
                                     DATE_FORMAT(c.update_at, '%d/%m/%Y') AS 'date_update',
                                     c.reference AS 'commande_reference' , c.livraison, c.adresse_livraison, 
                                     c.total_ht, c.total_ttc, c.etat, c.id_membre, dc.id_commande
                                     FROM detail_commande dc  
                                     INNER JOIN commande c ON  dc.id_commande = c.id_commande 
                                     AND dc.id_commande = :id_commande");

    $request->bindParam(":id_commande", $_GET['commande'], PDO::PARAM_INT);

    try {
        $request->execute();
    } catch (PDOException $exception) {
        header('Location: ' . URL . 'errors/error500.php');
        exit();
    }

    if ($request->rowCount() ==  0) {
        header('Location: ' . URL . 'errors/error404.php');
        exit();
    }

    $information = $request->fetch(PDO::FETCH_ASSOC);
    extract($information);

    if ($_SESSION['user']['id_membre'] !== $id_membre) {
        header('Location: ' . URL . 'compte.php');
        exit();
    }



    /* Affichage du détail des produits de la commande */

    $query = $bdd->prepare("SELECT *, prix AS 'detail_prix', quantite AS 'detail_quantite', total AS 'detail_total' FROM detail_commande WHERE id_commande = :id_commande");
    $query->bindParam(":id_commande", $_GET['commande'], PDO::PARAM_INT);

    try {
        $query->execute();
    } catch (PDOException $exception) {
        header('Location: ' . URL . 'errors/error500.php');
        exit();
    }

    if ($query->rowCount() ==  0) {
        header('Location: ' . URL . 'errors/error404.php');
        exit();
    }
} else {
    header('Location: ' . URL . 'errors/error404.php');
    exit();
}


$pageTitle = 'Espace Membre - Commande n°' . $commande_reference;
$pageMetaDesc = 'Retrouver le détail de votre commande.';
$bodyId = MEMBER_DETAIL_COMMANDE;

require_once('inc/header.inc.php');

?>

<section class="section-1-commande">

    <h2>Commande n° <?= $commande_reference; ?></h2>
    <hr>

    <p>

        Merci pour votre achat, nous vous l'enverrons dès que possible<br>
        Attention! Nous expédions les commandes en une seule fois,<br> au moment où l’article
        dont la date de sortie est la plus tardive est disponible.

    </p>

</section>

<section class="section-2-commande">

    <div class="background-commande">

        <div class="information-commande">

            <h3>Mon récapitulatif</h3>

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

        <div class="list-commande">

            <h3>Produits commandés</h3>
            <hr>

            <table>

                <thead>

                    <tr class="table-top-commande">

                        <th>Photo</th>
                        <th>Référence</th>
                        <th>Produit</th>
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

                        <tr class="table-details-commande">

                            <td data-label="Photo">

                                <figure>
                                    <a href="fiche-produit.php?id_produit=<?= $id_produit; ?>" title="Voir le produit">
                                        <img src="<?= $photo_produit; ?>"></a>
                                </figure>

                            </td>

                            <td data-label="Référence"><?= $reference_produit; ?></td>
                            <td data-label="Produit"><?= ucfirst($nom_produit); ?></td>
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
                <a class="btn-order" href="facture-commande.php?commande=<?= $id_commande; ?>">Télécharger la facture</a>

                <?php if (userIsAdmin()) : ?>

                    <a class="btn-order" href="admin/admin_commande.php">Retour sur les commandes</a>

                <?php else : ?>

                    <a class="btn-order" href="commande.php">Retour sur les commandes</a>

                <?php endif ?>


            </div>

        </div>

    </div>

</section>




<?php require_once('inc/footer.inc.php');  ?>