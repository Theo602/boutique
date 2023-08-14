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