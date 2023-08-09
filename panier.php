<?php

require_once('config/init.php');


$pageTitle = 'Panier';
$pageMetaDesc = 'Récapitulatif de votre panier.';
$bodyId = PANIER;


if (isset($_POST['ajoutPanier'])) {

    extract($_POST);

    $requestProduit = $bdd->prepare('SELECT * FROM produit WHERE id_produit = :id_produit');
    $requestProduit->bindParam(':id_produit', $id_produit, PDO::PARAM_INT);

    try {
        $requestProduit->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    $produit = $requestProduit->fetch(PDO::FETCH_ASSOC);
    extract($produit);

    addProduit($id_produit, $photo, $reference, $titre, $_POST['quantite'], $prix, $stock);

    header('location: panier.php');
    exit();
}

if (isset($_GET['action']) && !empty($_GET['action'])) {

    if (isset($_GET['id_produit']) && !empty($_GET['id_produit'])) {

        // Ajouter une quantité au produit
        if ($_GET['action'] == 'addQuantite') {

            $requestProduit = $bdd->prepare('SELECT * FROM produit WHERE id_produit = :id_produit');
            $requestProduit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);

            try {
                $requestProduit->execute();
            } catch (PDOException $exception) {
                header('Location: errors/error500.php');
                exit();
            }

            if ($requestProduit->rowCount() == 0) {
                header('Location: errors/error404.php');
                exit();
            } else {
                $produit = $requestProduit->fetch(PDO::FETCH_ASSOC);
                extract($produit);
            }

            addQuantiteProduit($id_produit, $stock, $titre);
        }

        // supprimer une quantité au produit
        if ($_GET['action'] == 'deleteQuantite') {

            $idProduit = array_search($_GET['id_produit'], $_SESSION['panier']['id_produit']);

            if ($idProduit   !== false) {
                $validsuppression = "Le produit <b>" . ucfirst($_SESSION['panier']['titre'][$idProduit]) . "</b> a été supprimé du panier";
                deleteQuantiteProduit($_GET['id_produit']);
            } else {
                header('Location: errors/error404.php');
                exit();
            }
        }

        // supprimer le produit du panier
        if ($_GET['action'] == 'deleteProduit') {

            $idProduit = array_search($_GET['id_produit'], $_SESSION['panier']['id_produit']);

            if ($idProduit   !== false) {
                $validsuppression = "Le produit <b>" . ucfirst($_SESSION['panier']['titre'][$idProduit]) . "</b> a été supprimé du panier";
                deleteProduit($_GET['id_produit']);
            } else {
                header('Location: errors/error404.php');
                exit();
            }
        }
    }
    // vider le produit du panier
    if ($_GET['action'] == 'deletePanier') {
        deletePanier();
    }
}

/* Calcul des prix total et tva */

if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    $totalHT = prixTotalHT();
    $tauxTva = tauxTva($totalHT);
    $totalTCC = prixTotalTCC($totalHT, $tauxTva);
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-panier">

    <h2>Panier</h2>
    <hr>

    <p>

        Retrouver vos articles afin de pouvoir passer une commande.<br>
        Attention! Nous expédions les commandes en une seule fois,<br> au moment où l’article
        dont la date de sortie est la plus tardive est disponible.

    </p>

</section>

<section class="section-2-panier">

    <div class="list-panier">

        <?php if (isset($_SESSION['content']['valid'])) : ?>
            <div class='message-succes'> <?= $_SESSION['content']['valid']; ?></div>
        <?php endif;
        unset($_SESSION['content']['valid']); ?>

        <?php if (isset($_SESSION['content']['error'])) : ?>
            <div class='message-error'> <?= $_SESSION['content']['error']; ?></div>
        <?php endif;
        unset($_SESSION['content']['error']); ?>

        <?php if (isset($validsuppression)) : ?>
            <div class='message-succes'> <?= $validsuppression; ?></div>
        <?php endif; ?>

        <h3>Liste de vos produits</h3>
        <hr>

        <table>

            <thead>

                <tr class="table-top-panier">

                    <th>Photo</th>
                    <th>Référence</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Option</th>
                    <th>Total</th>
                    <th>Supprimer</th>

                </tr>

            </thead>
            <tbody>

                <?php if (empty($_SESSION['panier']['id_produit'])) : ?>

                    <tr class="td-empty">
                        <td colspan="8">Le panier est vide</td>
                    </tr>

                <?php else : ?>

                    <?php for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) : ?>

                        <tr class="table-responsive">

                            <td>Produit : <?= $_SESSION['panier']['titre'][$i] ?></td>
                            <td><i class="fas fa-chevron-down"></td>

                        </tr>

                        <tr class="table-details-panier">

                            <td data-label="Photo">

                                <figure>
                                    <a href="fiche-produit.php?id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>" title="Voir le produit">
                                        <img src="<?= $_SESSION['panier']['photo'][$i] ?>" alt="<?= $_SESSION['panier']['titre'][$i] ?>"></a>
                                </figure>

                            </td>

                            <td data-label="Référence"><?= $_SESSION['panier']['reference'][$i] ?></td>
                            <td data-label="Produit"><?= ucfirst($_SESSION['panier']['titre'][$i]) ?></td>
                            <td data-label="Quantité"><?= $_SESSION['panier']['quantite'][$i] ?></td>
                            <td data-label="Prix"><?= $_SESSION['panier']['prix'][$i] ?>€</td>

                            <td data-label="Option">

                                <a href="panier.php?action=deleteQuantite&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>"><i class="fas fa-minus"></i></a>

                                <a href="panier.php?action=addQuantite&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>"><i class="fas fa-plus"></i></a>

                            </td>

                            <td data-label="Total"><?= produitTotal($_SESSION['panier']['id_produit'][$i]) ?>€</td>

                            <td data-label="Supprimer">
                                <a href="panier.php?action=deleteProduit&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>"><i class="fas fa-trash"></i></a>
                            </td>

                        </tr>

                    <?php endfor; ?>

                <?php endif; ?>

            </tbody>

        </table>

        <?php if (!empty($_SESSION['panier']['id_produit'])) : ?>

            <p class="panier-total">Total HT : <?= $totalHT; ?>€</p>
            <p class="panier-total">Total TVA (20%) : <?= $tauxTva; ?>€</p>
            <p class="panier-total">Total TTC : <?= $totalTCC; ?>€</p>

            <div class="order">

                <a class="btn-order" href="panier.php?action=deletePanier">Vider le panier</a>
                <a class="btn-order" href="boutique.php">Continuez mes achats</a>

                <?php if (userConnected()) : ?>
                    <a href="process-order?order=confirme" title="Passer la commande" class="btn-order"></i>Passer la commande</a>
                <?php else : ?>
                    <a class="btn-connect-panier" href="connexion.php">Connectez-vous pour pouvoir acheter</a>
                <?php endif; ?>
            </div>

        <?php else : ?>

            <p class="panier-vide">Votre panier est vide, remplissez le depuis la page <a href="boutique.php ">boutique</a>.</p>

        <?php endif; ?>

    </div>

</section>

<?php require_once('inc/footer.inc.php');  ?>