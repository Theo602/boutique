<?php

require_once('../config/init.php');


$pageTitle = 'Espace Admin - Compte';
$pageMetaDesc = 'Bienvenue sur votre tableau de bord.';
$bodyId = ADMIN_COMPTE;


if (!userIsAdmin()) {
    header('Location: ../errors/error403.php');
}


/* Affichage des informations du membres */

$id_membre = $_SESSION['user']['id_membre'];

$requestUser = $bdd->prepare('SELECT * FROM user WHERE id_membre = :id_membre ');
$requestUser->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);

try {
    $requestUser->execute();
} catch (PDOException $exception) {
    header('Location: ../errors/error500.php');
    exit();
}

$user = $requestUser->fetch(PDO::FETCH_ASSOC);
extract($user);

/* Affichage de la newsletters */

// Vérification de l'existence de l'email

$emailFind = $bdd->prepare("SELECT * FROM newsletter WHERE email = :email");
$emailFind->bindParam(':email', $email, PDO::PARAM_STR);

try {
    $emailFind->execute();
} catch (PDOException $exception) {
    header('Location: ../errors/error500.php');
    exit();
}

if ($emailFind->rowCount() == 1) {
    $newsletter = true;
} else {
    $newsletter = false;
}

/* supression de la newsletters */

if (isset($_GET['action']) || !empty($_GET['action'])) {

    if ($_GET['action'] == "deleteNewsletter") {

        $requestNewsletter = $bdd->prepare('DELETE FROM newsletter WHERE email = :email');
        $requestNewsletter->bindParam(":email", $email, PDO::PARAM_STR);

        try {
            $requestNewsletter->execute();
            $valid = "Votre abonnement à la newsletter a bien été supprimé";
            $_SESSION['content']['valid'] = $valid;
            header('Location: admin_compte.php?send=success');
            exit();
        } catch (PDOException $exception) {
            header('Location: admin_compte.php?send=error');
            exit();
        }
    }
}

/* Affichage des commandes */

$requestCommande = $bdd->prepare("SELECT *, DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande' FROM commande c INNER JOIN user u ON 
c.id_membre = u.id_membre AND c.id_membre = :id_membre ORDER BY c.id_commande DESC LIMIT 0, 6");

$requestCommande->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);

try {
    $requestCommande->execute();
} catch (PDOException $exception) {
    header('Location: ../errors/error500.php');
    exit();
}

$commandes = $requestCommande->fetchAll();


require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-compte">

        <div class="profil-information">

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

            <h3>Mon tableau de bord</h3>
            <hr class="separate-section-1">

            <p>Bonjour <?= ucfirst($prenom) . " " . ucfirst($nom); ?> !</p>
            <p>Depuis le tableau de bord, vous pouvez avoir un aperçu de vos récentes activités et mettre à jour les informations de votre compte. Sélectionnez un lien ci-dessous pour voir ou modifier les informations.</p>

            <hr class="separate-section-2">

            <div class="information">

                <div class="fiche-information">

                    <h3>Information du profil</h3>
                    <hr>

                    <p><?= ucfirst($prenom) . " " . ucfirst($nom); ?></p>
                    <p><?= $email; ?></p>
                    <p><?= $telephone; ?></p>

                    <a href="admin_profil.php#messageProfil">Editer</a>

                </div>

                <div class="fiche-information">

                    <h3>Adresse de livraison</h3>
                    <hr>

                    <p><?= $adresse; ?></p>
                    <p><?= $code_postal . " " . ucfirst($ville); ?></p>
                    <p><?= ucfirst($pays); ?></p>
                    <p><?= $telephone; ?></p>

                    <a href="admin_profil.php#messageAdresse">Editer</a>
                </div>

                <div class="fiche-information">

                    <h3>Adresse de facturation</h3>
                    <hr>

                    <p><?= $adresse; ?></p>
                    <p><?= $code_postal . " " . ucfirst($ville); ?></p>
                    <p><?= ucfirst($pays); ?></p>
                    <p><?= $telephone; ?></p>

                </div>

            </div>

            <hr>

            <p>Inscrit à la newsletter :

                <?php if ($newsletter) : ?>
                    Oui -
                    <a class="btnDesabonner" href="admin_compte.php?action=deleteNewsletter">se désabonner</a>
                <?php else : ?>
                    Non
                <?php endif ?>

            </p>

        </div>

    </section>

    <section class="section-2-compte">

        <div class="table-list">

            <h3>Liste des commandes</h3>
            <hr>

            <p>
                <a class="table-link" href="commande.php">Voir tout</a>
            </p>

            <table>

                <thead>
                    <tr class="table-top-commande">
                        <th>Commande nᵒ</th>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>TotalTcc</th>
                        <th>Status</th>
                        <th>Option</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($commandes)) : ?>

                        <?php foreach ($commandes as $commande) : extract($commande); ?>

                            <tr class="table-responsive">

                                <td>Commande nᵒ <?= $id_commande; ?></td>
                                <td><i class="fas fa-chevron-down"></td>

                            </tr>

                            <tr class="table-details-commande">
                                <td data-label="Commande nᵒ"><?= $id_commande; ?></td>
                                <td data-label="Référence"><?= $reference; ?></td>
                                <td data-label="Client"><?= $email; ?></td>
                                <td data-label="Date"><?= $date_commande; ?></td>
                                <td data-label="TotalTcc"><?= $total_ttc; ?></td>
                                <td data-label="Status"><?= $etat; ?></td>
                                <td data-label="Option">

                                    <a href="../detail-commande.php?commande=<?= $id_commande; ?>" title="Voir"><i class="fa fa-eye"></i></a>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr class="td-empty">
                            <td colspan="7">Aucune commande</td>
                        </tr>

                    <?php endif ?>

                </tbody>
            </table>

        </div>

    </section>

</section>

<?php require_once('inc/footer.inc.php');  ?>