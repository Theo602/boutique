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
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$categories = $dataCategorie->fetchAll();

/* Affichage des produits */

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}

if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {

    $url_page =  "boutique.php?categorie=" . $_GET['categorie'] . "&page=";
    $result = paginationCategorie($bdd, "produit", "categorie", $_GET['categorie'], $currentPage, 8);

    $requestProduit = $bdd->prepare('SELECT * FROM produit WHERE categorie = :categorie LIMIT :firstArticle, :limite');
    $requestProduit->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
    $requestProduit->bindValue(":firstArticle", $result['firstRow'], PDO::PARAM_INT);
    $requestProduit->bindValue(":limite", $result['limit'], PDO::PARAM_INT);

    try {
        $requestProduit->execute();
    } catch (PDOException $exception) {
        header('Location: ' . URL . 'errors/error500.php');
        exit();
    }

    if ($requestProduit->rowCount() == 0) {
        header('Location: ' . URL . 'errors/error404.php');
        exit();
    }
} else {

    $url_page = "boutique.php?page=";
    $result = pagination($bdd, "produit", $currentPage, 8);

    $requestProduit = $bdd->prepare('SELECT * FROM produit LIMIT :firstArticle, :limite');
    $requestProduit->bindValue(":firstArticle", $result['firstRow'], PDO::PARAM_INT);
    $requestProduit->bindValue(":limite", $result['limit'], PDO::PARAM_INT);

    try {
        $requestProduit->execute();
    } catch (PDOException $exception) {
        header('Location: ' . URL . 'errors/error500.php');
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
                                <a href="fiche-produit.php?id_produit=<?= $id_produit ?>">Voir le produit</a>

                            </div>

                        <?php endwhile; ?>

                    </div>
                    <?php require_once('inc/pagination.inc.php'); ?>
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