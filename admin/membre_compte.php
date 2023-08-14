<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ' . URL . 'errors/error403.php');
}


$pageTitle = 'Espace Admin - Membres';
$pageMetaDesc = 'Retrouver la liste des comptes des membres';
$bodyId = ADMIN_COMPTE_MEMBRE;


/* Affichage des roles des membres */

$dataRoles = $bdd->prepare('SELECT DISTINCT status FROM user');

try {
    $dataRoles->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

$roles = $dataRoles->fetchAll();

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}

/* Affichage des membres */

$url_page = "membre_compte.php?page=";
$result = pagination($bdd, "user", $currentPage, 6);

$requestUser = $bdd->prepare('SELECT * FROM user LIMIT :firstArticle, :limite');
$requestUser->bindValue(":firstArticle", $result['firstRow'], PDO::PARAM_INT);
$requestUser->bindValue(":limite", $result['limit'], PDO::PARAM_INT);

try {
    $requestUser->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
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

            <div class="search-link">
                <input class="inputForm" type="hidden" name="role_user" id="role_user" value="role_user">

                <select name="search-select" id="search-select">
                    <option value="all" selected>Toutes les membres</option>

                    <?php foreach ($roles as $role) : ?>

                        <option value="<?= $role['status'] ?>"><?= ($role['status'] == 0) ? 'Client' : 'Administrateur'; ?></option>

                    <?php endforeach; ?>

                </select>

            </div>

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

                <tbody class="search">

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

                </tbody>
            </table>

        </div>

        <?php require_once('../inc/pagination.inc.php'); ?>

    </section>

</section>

<?php require_once('inc/footer.inc.php');  ?>