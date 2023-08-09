<?php

require_once('config/init.php');


if (!userConnected()) {
    header('Location: errors/error403.php');
    exit();
}


/* Affichage du détail de la commande  */

if (isset($_GET['commande']) && !empty($_GET['commande'])) {

    /* Affichage des informations de la commande */

    $request = $bdd->prepare("SELECT DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande', 
                                     DATE_FORMAT(c.update_at, '%d/%m/%Y') AS 'date_update',
                                     c.reference AS 'commande_reference' , c.facture AS 'commande_facture', 
                                     c.livraison, c.adresse_livraison, c.total_ht, c.total_ttc, c.etat, c.id_membre, dc.id_commande
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

    if ($_SESSION['user']['id_membre'] !== $id_membre && $_SESSION['user']['status'] !== "1") {
        header('Location: compte.php');
        exit();
    }

    /* Affichage du détail des produits de la commande */

    $query = $bdd->prepare("SELECT *, prix AS 'detail_prix', quantite AS 'detail_quantite', total AS 'detail_total' FROM detail_commande WHERE id_commande = :id_commande");

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


$pageTitle = 'Facture - Commande n° ' . $commande_reference;
$pageMetaDesc = 'Télécharger la facture de votre commande.';
$bodyId = MEMBER_FACTURE_COMMANDE;

require_once('inc/header_pdf.inc.php');

?>

<section class="section-1-pdf">

    <h1>Boutique T-Commerce</h1>

    <p>
        24 rue de la foret <br>
        35000 Rennes
    </p>

    <hr>

    <h2>Commande n° <?= $commande_reference; ?></h2>
    <hr>

</section>

<section class="section-2-pdf">

    <div class="background-pdf">

        <div class="information-pdf">

            <h3>Mon récapitulatif</h3>

            <hr>

            <div class="resume-pdf">

                <div class="fiche-information-pdf">

                    <h3>Information</h3>
                    <hr>

                    <p>Commande passée le <?= $date_commande; ?></p>

                    <p>Référence : <?= $commande_reference ?></p>
                    <p>Facture : <?= $commande_facture ?></p>
                </div>

                <div class="fiche-information-pdf">

                    <h3>Adresse de livraison</h3>
                    <hr>

                    <p><?= $adresse_livraison; ?></p>

                </div>

                <div class="fiche-information-pdf">

                    <h3>Adresse de facturation</h3>
                    <hr>

                    <p><?= $adresse_livraison; ?></p>

                </div>

                <div class="fiche-information-pdf">

                    <h3>Mode de livraison</h3>
                    <hr>

                    <p><?= $livraison; ?></p>

                </div>

            </div>

        </div>

        <div class="list-pdf clear">

            <h3>Vos produits</h3>
            <hr>

            <table>

                <thead>

                    <tr class="table-top-pdf">

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

                        <tr class="table-details-pdf">

                            <td data-label="Photo">

                                <figure>
                                    <img src="<?= $photo_produit; ?>">
                                </figure>

                            </td>

                            <td data-label="Référence"><?= $reference_produit; ?></td>
                            <td data-label="Nom du produit"><?= ucfirst($nom_produit); ?></td>
                            <td data-label="Quantité"><?= $detail_quantite; ?></td>
                            <td data-label="Prix"><?= $detail_prix; ?> €</td>
                            <td data-label="Total"><?= $detail_total; ?> €</td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

            <p class="panier-total-pdf">Total HT : <?= $total_ht; ?>€</p>
            <p class="panier-total-pdf">Total TVA (20%) : <?= tauxTva($total_ht); ?>€</p>
            <p class="panier-total-pdf">Total TTC : <?= $total_ttc; ?>€</p>

        </div>

    </div>

</section>