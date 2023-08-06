<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ../errors/error403.php');
}


$pageTitle = 'Espace Admin - Membres';
$pageMetaDesc = 'Retrouver la liste des comptes des membres';
$bodyId = ADMIN_COMPTE_MEMBRE;


/* Affichage des produits */

$requestUser = $bdd->prepare('SELECT * FROM user');

try {
    $requestUser->execute();
} catch (PDOException $exception) {
    header('Location: ../errors/error500.php');
    exit();
}

$users = $requestUser->fetchAll();

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-compte">

        <div class="table-list">

            <h3>Liste des membres</h3>
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
                    <tr class="table-top-user">
                        <th>Membre nᵒ</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Option</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($users)) : ?>

                        <?php foreach ($users as $user) : extract($user); ?>

                            <tr class="table-responsive">

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

                </tbody>
            </table>

        </div>

    </section>

</section>

<?php require_once('inc/footer.inc.php');  ?>