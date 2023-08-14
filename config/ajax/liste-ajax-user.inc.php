<?php if (!empty($users)) : ?>

    <?php foreach ($users as $user) : extract($user); ?>

        <tr class=" table-responsive">

            <td>Membre nᵒ <?= $id_membre; ?></td>
            <td><i class="fas fa-chevron-down"></td>

        </tr>

        <tr class="table-details-user">
            <td data-label="Membre nᵒ"><?= $id_membre; ?></td>
            <td data-label="Prénom"><?= ucfirst($prenom); ?></td>
            <td data-label="Nom"><?= ucfirst($nom); ?></td>
            <td data-label="Email"><?= $email; ?></td>
            <td data-label="Status"><?= ($user['status'] == 0) ? 'Client' : 'Administrateur'; ?></td>
            <td data-label="Option">

                <a href="gestion_membre.php?action=edit&membre=<?= $id_membre ?>" title="Modifier"><i class="fas fa-pencil"></i></a>

                <a class="btnDelete" href="gestion_membre.php?action=suppression&membre=<?= $id_membre ?>" title="Supprimer"> <i class="fa fa-trash-can"></i></a>

            </td>
        </tr>

    <?php endforeach; ?>

<?php else : ?>

    <tr class="td-empty">
        <td colspan="6">Aucun membre</td>
    </tr>

<?php endif ?>