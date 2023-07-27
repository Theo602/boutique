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

    $dataProduit = $bdd->prepare('SELECT * FROM produit WHERE categorie = :categorie');
    $dataProduit->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);

    try {
        $dataProduit->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    if ($dataProduit->rowCount() == 0) {
        header('Location: errors/error404.php');
        exit();
    }
} else {

    $dataProduit = $bdd->prepare('SELECT * FROM produit');

    try {
        $dataProduit->execute();
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

                <?php if (!empty($dataProduit)) : ?>

                    <div class="block-produit-boutique">

                        <?php while ($product = $dataProduit->fetch(PDO::FETCH_ASSOC)) : ?>

                            <div class="fiche-produit-boutique">

                                <figure>
                                    <img src="<?= $product['photo'] ?>" alt="<?= $product['titre'] ?>">
                                </figure>

                                <h3 class="titre_produit"><?= ucfirst($product['titre']) ?></h3>
                                <p><?= ucfirst($product['public']) ?></p>
                                <p><?= $product['prix'] ?>€</p>
                                <a href="<?= "produit.php?id_produit=" . $product['id_produit'] ?>">Voir le produit</a>

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