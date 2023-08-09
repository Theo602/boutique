<?php

require_once('config/init.php');


$pageTitle = 'Achat de la commande';
$pageMetaDesc = 'Achat de la commande';
$bodyId = PROCESS_COMMANDE;


/* Redirection si le membre n'est pas connecté */

if (!userConnected()) {
    header('Location: errors/error403.php');
    exit();
}

/* Redirection si le panier n'existe pas en session */

if (!isset($_SESSION['panier']) && empty($_SESSION['panier'])) {
    header('location: panier.php');
    exit();
}


/* Calcul des prix total et tva */

$totalHT = prixTotalHT();
$tauxTva = tauxTva($totalHT);
$totalTCC = prixTotalTCC($totalHT, $tauxTva);


if (isset($_GET['order']) && !empty($_GET['order'])) {

    if ($_GET['order'] == 'confirme') {

        $id_membre = $_SESSION['user']['id_membre'];

        $requestUser = $bdd->prepare('SELECT * FROM user WHERE id_membre = :id_membre ');
        $requestUser->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);

        try {
            $requestUser->execute();
        } catch (PDOException $exception) {
            header('Location: errors/error500.php');
            exit();
        }

        $user = $requestUser->fetch(PDO::FETCH_ASSOC);
        extract($user);

        /* Adresse de livraison et de facturation */
        $adresseLivraison = ucfirst($prenom) . ' ' . ucfirst($nom);
        $adresseLivraison .= '<br>' . $adresse;
        $adresseLivraison .= '<br>' . $code_postal . ' ' . ucfirst($ville);

        /* Mode de livraison */
        $modeDeLivraision = 'Colissimo - Domicile sans signature';
    }
}

if (isset($_POST["payer"])) {

    /* Controle du stock de l'article */

    $errorStock = "";

    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {

        $request = $bdd->query(" SELECT stock FROM produit WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);

        $stock = $request->fetch(PDO::FETCH_ASSOC);

        if ($stock['stock'] < $_SESSION['panier']['quantite'][$i]) {

            if ($stock['stock'] > 0) {
                $errorStock .= "La quantité du produit <b>" . ucfirst($_SESSION['panier']['titre'][$i]) . "</b> a été modifié car le stock est insuffisant. <br>";
                $_SESSION['panier']['quantite'][$i] = $stock['stock'];
            } else {
                $errorStock .= "Le produit <b>" . ucfirst($_SESSION['panier']['titre'][$i]) . "</b> a été supprimé car le produit est en rupture de stock. <br>";

                deleteProduit($_SESSION['panier']['id_produit'][$i]);
                $i--;
            }

            $errorPanier = true;
        }
    }

    if (!isset($errorPanier)) {

        $date_creation = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $date_reference = $date_creation->format('Ymd');
        $date_creation = $date_creation->format('Y-m-d H:i:s');
        $reference_commande = $date_reference . "-" . chaineReference(12);
        $facture_commande = chaineFacture(12);

        $etat = "payé";

        $request = $bdd->prepare("INSERT INTO commande (id_membre, reference, facture, livraison, adresse_livraison, total_ht,  total_ttc, etat, created_at, update_at) 
        VALUES (:id_membre, :reference, :facture, :livraison, :adresse_livraison, :total_ht,  :total_ttc, :etat, :created_at, :update_at)");

        $request->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);
        $request->bindParam(":reference", $reference_commande, PDO::PARAM_STR);
        $request->bindParam(":facture", $facture_commande, PDO::PARAM_STR);
        $request->bindParam(":livraison", $modeDeLivraision, PDO::PARAM_STR);
        $request->bindParam(":adresse_livraison", $adresseLivraison, PDO::PARAM_STR);
        $request->bindParam(":total_ht", $totalHT, PDO::PARAM_INT);
        $request->bindParam(":total_ttc", $totalTCC, PDO::PARAM_INT);
        $request->bindParam(":etat", $etat, PDO::PARAM_STR);
        $request->bindParam(":created_at", $date_creation, PDO::PARAM_STR);
        $request->bindParam(":update_at", $date_creation, PDO::PARAM_STR);

        try {
            $request->execute();
            $id_commande = $bdd->lastInsertId();
        } catch (PDOException $exception) {
            header('Location: errors/error500.php');
            exit();
        }

        for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {

            $produitTotal = produitTotal($_SESSION['panier']['id_produit'][$i]);

            $query = $bdd->prepare("INSERT INTO detail_commande (id_commande, id_produit, reference_produit, nom_produit, photo_produit, quantite, prix, total) VALUES (:id_commande, :id_produit, :reference_produit, :nom_produit, :photo_produit, :quantite, :prix, :total)");

            $query->bindParam(":id_commande", $id_commande, PDO::PARAM_INT);
            $query->bindParam(":id_produit", $_SESSION['panier']['id_produit'][$i], PDO::PARAM_INT);
            $query->bindParam(":reference_produit", $_SESSION['panier']['reference'][$i], PDO::PARAM_STR);
            $query->bindParam(":nom_produit", $_SESSION['panier']['titre'][$i], PDO::PARAM_STR);
            $query->bindParam(":photo_produit", $_SESSION['panier']['photo'][$i], PDO::PARAM_STR);
            $query->bindParam(":quantite", $_SESSION['panier']['quantite'][$i], PDO::PARAM_INT);
            $query->bindParam(":prix", $_SESSION['panier']['prix'][$i], PDO::PARAM_INT);
            $query->bindParam(":total", $produitTotal, PDO::PARAM_INT);

            try {
                $query->execute();
            } catch (PDOException $exception) {
                header('Location: errors/error500.php');
                exit();
            }

            /* On enlève du stock la quantité en fonction de la commande */

            $requestProduit = $bdd->exec("UPDATE produit SET stock = stock - " . $_SESSION['panier']['quantite'][$i] . " WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);
        }

        /* On vide le panier */

        unset($_SESSION['panier']);

        /* On envoie un mail de confirmation */

        $to = $email;

        $entete = 'MIME-Version: 1.0' . "\r\n";
        $entete .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $entete .= 'From: confirmation-commande@boutique-tcommerce.fr' . "\r\n";
        $entete .= 'Reply-to: confirmation-commande@boutique-tcommerce.fr';

        $sujet = 'Confirmation de votre commande n° ' . $reference;

        $message = '<html>

        <head>
            <title>Confirmation de commande</title>
        </head>

        <body>

            <h1>Confirmation de commande</h1>

            <h3>Bonjour <strong>' . $prenom . " " . $nom . ',</strong></h3><br>

            <p>Nous vous remercions de votre commande n° <b>' . $reference . '</b>.</p>

            <p>Votre commande sera livrée par <b>' . $modeDeLivraision . '</b> à l\'adresse suivante : </p>

            <p><b>' . $adresseLivraison . '</b></p>
            <p>Vous pouvez sur votre commande sur votre <a href="' . URL . '/connexion">compte</a></p>
            <p>Si vous avez des questions, n\'hésitez pas à nous contacter sur support@boutique-tcommerce.fr ou bien appelez-nous au 01.36.98.52.40 du lundi au vendredi, de 10h à 18h.</p>

            <p>Merci pour votre confiance et à bientot sur <a href="' . URL . '">La boutique Tcommerce</a></p>

        </body>

        </html>';

        // mail($to, $sujet, $message, $entete);

        $_SESSION['commande']['id_commande'] = $id_commande;

        header('Location: validation-commande.php');
        exit();
    }
}


require_once('inc/header.inc.php');

?>

<section class="section-1-commande">

    <h2>Validation de la commande</h2>
    <hr>

    <p>

        Récapitulatif de votre commande, avant de passer au paiement.<br>
        Attention! Nous expédions les commandes en une seule fois,<br> au moment où l’article
        dont la date de sortie est la plus tardive est disponible.

    </p>

</section>

<section class="section-2-commande">

    <div class="background-commande">

        <div class="information-commande">

            <h3>Mon récapitulatif</h3>

            <hr>

            <?php if (isset($errorStock) && !empty($errorStock)) : ?>
                <div class='message-error'> <?= $errorStock; ?></div>
            <?php endif; ?>

            <div class="resume-commande">

                <div class="fiche-information">

                    <h3>Adresse de livraison</h3>
                    <hr>

                    <p><?= isset($adresseLivraison) ? $adresseLivraison : ''; ?></p>

                </div>

                <div class="fiche-information">

                    <h3>Adresse de facturation</h3>
                    <hr>

                    <p><?= isset($adresseLivraison) ? $adresseLivraison : ''; ?></p>

                </div>

                <div class="fiche-information">

                    <h3>Mode de livraison</h3>
                    <hr>

                    <p><?= isset($modeDeLivraision) ? $modeDeLivraision : ''; ?></p>

                </div>

            </div>


        </div>

        <div class="list-commande">

            <h3>Vos produits</h3>
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

                    <?php for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) : ?>

                        <tr class="table-responsive">

                            <td>Produit : <?= $_SESSION['panier']['titre'][$i] ?></td>
                            <td><i class="fas fa-chevron-down"></td>

                        </tr>

                        <tr class="table-details-commande">

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
                            <td data-label="Total"><?= produitTotal($_SESSION['panier']['id_produit'][$i]) ?>€</td>

                        </tr>

                    <?php endfor; ?>

                </tbody>

            </table>

            <p class="panier-total">Total HT : <?= $totalHT; ?>€</p>
            <p class="panier-total">Total TVA (20%) : <?= $tauxTva; ?>€</p>
            <p class="panier-total">Total TTC : <?= $totalTCC; ?>€</p>

            <form class="order" action="" method="POST">
                <a class="btn-achat" href="panier.php?action=returnPanier">Annuler</a>
                <input class="btn-achat" type="submit" name="payer" value="Payer">
            </form>


        </div>

    </div>

</section>

<?php require_once('inc/footer.inc.php');  ?>