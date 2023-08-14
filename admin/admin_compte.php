<?php

require_once('../config/init.php');


$pageTitle = 'Espace Admin - Compte';
$pageMetaDesc = 'Bienvenue sur votre tableau de bord.';
$bodyId = ADMIN_COMPTE;


if (!userIsAdmin()) {
    header('Location: ' . URL . 'errors/error403.php');
}


/* Affichage des informations du membres */

$id_membre = $_SESSION['user']['id_membre'];

$requestUser = $bdd->prepare('SELECT * FROM user WHERE id_membre = :id_membre ');
$requestUser->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);

try {
    $requestUser->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
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
    header('Location: ' . URL . 'errors/error500.php');
    exit();
}

if ($emailFind->rowCount() == 1) {
    $newsletter = true;
} else {
    $newsletter = false;
}

/* Supression de la newsletters */

if (isset($_GET['action']) || !empty($_GET['action'])) {

    if ($_GET['action'] == "deleteNewsletter") {

        $requestNewsletter = $bdd->prepare('DELETE FROM newsletter WHERE email = :email');
        $requestNewsletter->bindParam(":email", $email, PDO::PARAM_STR);

        try {
            $requestNewsletter->execute();
            $valid = "Votre abonnement à la newsletter a bien été supprimé";
            $_SESSION['content']['valid'] = $valid;
            header('Location: ' . URL . 'admin/admin_compte.php?send=success');
            exit();
        } catch (PDOException $exception) {
            header('Location: ' . URL . 'admin/admin_compte.php?send=error');
            exit();
        }
    }
}

/* Affichage des commandes */

$requestCommande = $bdd->prepare("SELECT c.id_commande, c.reference, c.total_ttc, c.etat, u.email, 
                                  DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande' FROM commande c 
                                  INNER JOIN user u ON c.id_membre = u.id_membre 
                                  AND c.id_membre = :id_membre
                                  ORDER BY id_commande  DESC LIMIT 0, 6");

$requestCommande->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);

try {
    $requestCommande->execute();
} catch (PDOException $exception) {
    header('Location: ' . URL . 'errors/error500.php');
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

            <div class="search-link">
                <input class="inputForm" type="hidden" name="search_commande" id="commande_search" value="commande_compte">
                <input class="inputForm" type="hidden" name="search_id" id="id_search" value="<?= $id_membre; ?>">

                <select name="search-select" id="search-select">
                    <option value="all" selected>Toutes les commandes</option>
                    <option value="payé">Payé</option>
                    <option value="en cours de traitement">En cours de traitement</option>
                    <option value="envoyé">Envoyé</option>
                    <option value="livré">Livré</option>
                    <option value="annulé">Annulé</option>
                </select>

                <p>
                    <a class="table-link" href="admin_commande.php">Voir tout</a>
                </p>

            </div>

            <table>

                <thead>
                    <tr class="table-top-commande">
                        <th>Commande nᵒ</th>
                        <th>Référence</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>TotalTcc</th>
                        <th>Status</th>
                        <th>Option</th>
                    </tr>
                </thead>

                <tbody class="search">

                    <?php if (!empty($commandes)) : ?>

                        <?php foreach ($commandes as $commande) : extract($commande); ?>

                            <tr class="table-responsive">

                                <td>Commande nᵒ <?= $id_commande; ?></td>
                                <td><i class="fas fa-chevron-down"></td>

                            </tr>

                            <tr class="table-details-commande">
                                <td data-label="Commande nᵒ"><?= $id_commande; ?></td>
                                <td data-label="Référence"><?= $reference; ?></td>
                                <td data-label="Email"><?= $email; ?></td>
                                <td data-label="Date"><?= $date_commande; ?></td>
                                <td data-label="TotalTcc"><?= $total_ttc; ?></td>
                                <td data-label="Status">
                                    <p class="table-etat <?php
                                                            if ($etat === 'livré') {
                                                                echo 'etat-livrer';
                                                            } elseif ($etat === 'envoyé') {
                                                                echo 'etat-envoyer';
                                                            } elseif ($etat === 'en cours de traitement') {
                                                                echo 'etat-pending';
                                                            } elseif ($etat === 'annulé') {
                                                                echo 'etat-annuler';
                                                            } else {
                                                                echo 'etat-payer';
                                                            }
                                                            ?>">
                                        <?= ucfirst($etat); ?>
                                    </p>
                                </td>
                                <td data-label="Option">

                                    <a href="../detail-commande.php?commande=<?= $id_commande; ?>" title="Voir"><i class="fa fa-eye"></i></a>
                                    <a href="../facture-commande.php?commande=<?= $id_commande; ?>" title="Facture"><i class="fas fa-file-pdf"></i></a>
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