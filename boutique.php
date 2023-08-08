<?php

require_once('config/init.php');


$pageTitle = 'Boutique';
$pageMetaDesc = 'Découvrez nos produits de prêt-à-porter pour les jeunes adultes.';
$bodyId = BOUTIQUE_PAGE;


/* Affichage des catégorie */

$dataCategorie = $bdd->prepare('SELECT DISTINCT categorie FROM produit');

try {
    $dataCategorie->execute();
} catch (PDOException $exception) {
    header('Location: errors/error500.php');
    exit();
}

$categories = $dataCategorie->fetchAll();

/* Affichage des produits */

if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {

    $requestProduit = $bdd->prepare('SELECT * FROM produit WHERE categorie = :categorie');
    $requestProduit->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);

    try {
        $requestProduit->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    if ($requestProduit->rowCount() == 0) {
        header('Location: errors/error404.php');
        exit();
    }
} else {

    $requestProduit = $bdd->prepare('SELECT * FROM produit');

    try {
        $requestProduit->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-boutique">

    <h2 class="title-product">Nos produits</h2>
    <hr>

    <div class="container-left-right">

        <section class="section-front-left">

            <section class="section-1-categorie">

                <h3>Nos catégories</h3>
                <hr>

                <nav class="menu-categorie">

                    <ul>
                        <?php foreach ($categories as $categorie) : ?>
                            <li><a href="<?= "boutique.php?categorie=" . $categorie['categorie'] ?>"><?= ucfirst($categorie['categorie']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>

                </nav>

            </section>

        </section>

        <section class="section-front-right">

            <section class="section-1-produit">

                <?php if (!empty($requestProduit)) : ?>

                    <div class="block-produit-boutique">

                        <?php while ($produit = $requestProduit->fetch(PDO::FETCH_ASSOC)) : extract($produit); ?>

                            <div class="fiche-produit-boutique">

                                <figure>
                                    <img src="<?= $photo ?>" alt="<?= $titre ?>">
                                </figure>

                                <h3 class="titre_produit"><?= ucfirst($titre) ?></h3>
                                <p><?= ucfirst($public) ?></p>
                                <p><?= $prix ?>€</p>
                                <a href="<?= "fiche-produit.php?id_produit=" . $id_produit ?>">Voir le produit</a>

                            </div>

                        <?php endwhile; ?>

                    </div>

                <?php else : ?>

                    <div class="message-info-produit">

                        <p>Aucun produit pour le moment</p>

                    </div>

                <?php endif ?>

            </section>

        </section>

    </div>

</section>

<?php require_once('inc/footer.inc.php');  ?>