<?php

require_once('config/init.php');


/* Affichage des produits */


if (isset($_GET['id_produit']) && !empty($_GET['id_produit'])) {

    $requestProduit = $bdd->prepare('SELECT * FROM produit WHERE id_produit = :id_produit');
    $requestProduit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);

    try {
        $requestProduit->execute();
    } catch (PDOException $exception) {
        header('Location: ' . URL . 'errors/error500.php');
        exit();
    }

    if ($requestProduit->rowCount() == 0) {
        header('Location: ' . URL . 'errors/error404.php');
        exit();
    } else {
        $produit = $requestProduit->fetch(PDO::FETCH_ASSOC);
        extract($produit);
    }
} else {
    header('Location: ' . URL . 'errors/error404.php');
}


$pageTitle = 'Produit: ' . $titre;
$pageMetaDesc = 'Découvrez les caractéristique et les informations du produit.';
$bodyId = PRODUIT_PAGE;


require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-produit">

    <a href="boutique.php" class="return-boutique">Retourner sur les produits</a>

    <article class="fiche-produit">

        <div class="produit-global">

            <figure>
                <img src="<?= $photo ?>" alt="<?= $titre ?>">
            </figure>

            <div class="produit-infos">

                <h2><?= $titre ?></h2>
                <h3 class="produit-prix"><?= $prix; ?>€</h3>
                <p class="produit-text">Public : <?= ucfirst($public); ?></p>
                <p class="produit-text">Couleur : <?= ucfirst($couleur); ?></p>
                <p class="produit-text">Taille : <?= $taille; ?></p>

                <p class="produit-text">Description : <br> <?= $description ?></p>


                <?php if ($stock != 0 && $stock <= 10) : ?>

                    <p class="produit-text">
                        Attention quantité limitée, il reste
                        <?= ($stock == 1) ? $stock . " exemplaire"
                            : $stock . " exemplaires";  ?>
                    </p>

                <?php elseif ($stock > 10) : ?>

                    <p class="produit-text">En stock</p>

                <?php endif ?>

                <?php if ($stock > 0) : ?>

                    <form action="panier" method="POST">

                        <input type="hidden" id="id_produit" name="id_produit" value="<?= $id_produit; ?>">

                        <div class="produit-stock-submit">

                            <select class="produit-select" name="quantite" id="quantite">
                                <?php for ($i = 1; $i <= $stock; $i++) : ?>

                                    <option value="<?= $i ?>"><?= $i ?></option>

                                <?php endfor; ?>
                            </select>

                            <input class="produit-submit" type="submit" name="ajoutPanier" value="AJOUTER AU PANIER">

                        </div>

                    </form>

                <?php else : ?>
                    <p class="produit-rupture">Produit indisponible</p>
                <?php endif ?>

            </div>

        </div>

    </article>

</section>

<?php require_once('inc/footer.inc.php');  ?>