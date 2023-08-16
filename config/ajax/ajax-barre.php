<?php

require_once('../init.php');

if (isset($_GET['search']) && !empty($_GET['search'])) {

    $search = (string) trim($_GET['search']);
    $searchProduit = "%" . $search . "%";

    $sqlProduit = "SELECT id_produit, titre, photo FROM produit WHERE titre LIKE :search LIMIT 0, 6";

    $request = $bdd->prepare($sqlProduit);
    $request->bindParam(":search", $searchProduit, PDO::PARAM_STR);
    $request->execute();

    if ($request->rowCount() == 0) {
?>
        <div class="result">
            <p class="result-no-found">Aucun résultat pour : <?= $search; ?></p>
        </div>
        <?php
    } else {
        $produits = $request->fetchAll();

        foreach ($produits as $produit) {
            extract($produit);
        ?>
            <a class="link-result" href="fiche-produit.php?id_produit=<?= $id_produit ?>">

                <div class="result">
                    <figure>
                        <img src="<?= $photo; ?>" alt="Produit : <?= $titre; ?>">
                    </figure>

                    <p class="result-found">
                        <?= ucfirst($titre); ?>
                    </p>
                </div>
            </a>

<?php
        }
    }
}
