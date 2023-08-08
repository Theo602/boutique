<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ../errors/error403.php');
}


$pageTitle = 'Espace Admin - Newsletter';
$pageMetaDesc = 'Retrouver la liste des newsletters des membres';
$bodyId = ADMIN_GESTION_NEWSLETTER;


/* Affichage des newsletters */

$requestNewsletter = $bdd->prepare("SELECT *, DATE_FORMAT(created_at, '%d/%m/%Y') AS created_at FROM newsletter");

try {
    $requestNewsletter->execute();
} catch (PDOException $exception) {
    header('Location: ../errors/error500.php');
    exit();
}

$newsletters = $requestNewsletter->fetchAll();

/* Supression des newsletters */

if (isset($_GET['action']) || !empty($_GET['action'])) {

    if ($_GET['action'] == "delete") {

        if (isset($_GET['id_newsletter']) && !empty($_GET['id_newsletter'])) {

            $query = $bdd->prepare('DELETE FROM newsletter WHERE id_newsletter = :id_newsletter');
            $query->bindParam(':id_newsletter', $_GET['id_newsletter'], PDO::PARAM_INT);

            try {
                $query->execute();

                $validSupp = "La <b>newsletter nᵒ " . $_GET['id_newsletter'] . "</b> a bien été supprimée";
                $_SESSION['content']['valid'] = $validSupp;

                header('Location: gestion_newsletter.php?send=success');
                exit();
            } catch (PDOException $exception) {

                $errorSupp = "Erreur lors de la suppression";
                $_SESSION['content']['error'] = $errorSupp;

                header('Location: gestion_newsletter.php?send=error');
                exit();
            }
        } else {
            header('Location: ../errors/error404.php');
            exit();
        }
    }
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-newsletter">

        <div class="table-list">

            <h3>Liste des newsletters</h3>
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
                    <tr class=" table-top-newsletter">
                        <th>Newsletter nᵒ</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Option</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($newsletters)) : ?>

                        <?php foreach ($newsletters as $newsletter) : extract($newsletter); ?>

                            <tr class="table-responsive">

                                <td>Newsletter nᵒ <?= $id_newsletter; ?></td>
                                <td><i class="fas fa-chevron-down"></td>

                            </tr>

                            <tr class="table-details-newsletter">
                                <td data-label="Newsletter nᵒ"><?= $id_newsletter; ?></td>
                                <td data-label="Email"><?= $email; ?></td>
                                <td data-label="Date"><?= $created_at; ?></td>
                                <td data-label="Option">

                                    <a class="btnDelete" href="gestion_newsletter.php?action=delete&id_newsletter=<?= $id_newsletter ?>" title="Supprimer"> <i class="fa fa-trash-can"></i></a>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr class="td-empty">
                            <td colspan="4">Aucune commande</td>
                        </tr>

                    <?php endif ?>

                </tbody>
            </table>

        </div>

    </section>

</section>
</div>

<?php require_once('inc/footer.inc.php');  ?>