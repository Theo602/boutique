<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ' . URL . 'errors/error403.php');
}


$pageTitle = 'Espace Admin - Boutique';
$pageMetaDesc = 'Retrouver la liste des produits de la boutique';
$bodyId = ADMIN_BOUTIQUE;


/* Affichage des produits */

$RequestProduit = $bdd->prepare('SELECT * FROM produit');

try {
    $RequestProduit->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$produits = $RequestProduit->fetchAll();

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-boutique">

        <div class="table-list">

            <h3>Liste des produits</h3>
            <hr>

            <?php if (isset($_GET['send']) && ($_GET['send'] == "success")) : ?>

                <?php if (isset($_SESSION['content']['valid'])) : ?>
                    <div class='message-succes'> <?= $_SESSION['content']['valid']; ?></div>
                <?php endif;
                unset($_SESSION['content']['valid']); ?>

            <?php endif; ?>

            <?php if (isset($_GET['send']) && ($_GET['send'] == "error")) : ?>

                <?php if (isset($_SESSION['content']['error'])) : ?>
                    <div class='message-error'> <?= $_SESSION['content']['error']; ?></div>
                <?php endif;
                unset($_SESSION['content']['error']); ?>

            <?php endif; ?>

            <table>

                <thead>
                    <tr class="table-top">
                        <th>Produit nᵒ</th>
                        <th>Référence</th>
                        <th>Produit</th>
                        <th>Couleur</th>
                        <th>Taille</th>
                        <th>Public</th>
                        <th>Catégorie</th>
                        <th>Photo</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Option</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($produits)) : ?>

                        <?php foreach ($produits as $produit) : extract($produit); ?>

                            <tr class="table-responsive">

                                <td>Produit nᵒ <?= $id_produit; ?></td>
                                <td><i class="fas fa-chevron-down"></td>

                            </tr>
                            <tr class="table-details">
                                <td data-label="Produit nᵒ"><?= $id_produit; ?></td>
                                <td data-label="Référence"><?= ucfirst($reference); ?></td>
                                <td data-label="Produit"><?= ucfirst($titre); ?></td>
                                <td data-label="Couleur"><?= ucfirst($couleur); ?></td>
                                <td data-label="Taille"><?= ucfirst($taille); ?></td>
                                <td data-label="Public"><?= ucfirst($public); ?></td>
                                <td data-label="Catégorie"><?= ucfirst($categorie); ?></td>
                                <td data-label="Photo"><img src="<?= $photo; ?>" alt="Produit : <?= ucfirst($titre); ?>"></td>
                                <td data-label="Prix"><?= $prix; ?></td>
                                <td data-label="Stock"><?= $stock; ?></td>
                                <td data-label="Option">

                                    <a href="<?= "../fiche-produit.php?id_produit=" . $id_produit ?>" title="Voir"><i class="fa fa-eye"></i></a>

                                    <a href="gestion_produit.php?action=modifier&id_produit=<?= $id_produit ?>" title="Modifier"><i class="fas fa-pencil"></i></a>

                                    <a class="btnDelete" href="gestion_produit.php?action=supprimer&id_produit=<?= $id_produit ?>" title="Supprimer"> <i class="fa fa-trash-can"></i></a>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr class="td-empty">
                            <td colspan="11">Aucun produit</td>
                        </tr>

                    <?php endif ?>

                </tbody>
            </table>

        </div>

    </section>

</section>

<?php require_once('inc/footer.inc.php');  ?>