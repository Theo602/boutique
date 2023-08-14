<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ' . URL . 'errors/error403.php');
}


$pageTitle = 'Espace Admin - Gestion des commandes';
$pageMetaDesc = 'Modifier - supprimer les commandes des commandes';
$bodyId = ADMIN_GESTION_COMMANDE;


$error = [];


if (!isset($_GET['action']) || empty($_GET['action'])) {
    header('Location: ' . URL . 'admin/membre_commande.php');
    exit();
}

if ($_GET['action'] == "edit" || $_GET['action'] == "suppression") {

    // Modification d'une commande

    if ($_GET['action'] == "edit") {

        if (isset($_GET['commande']) && !empty($_GET['commande'])) {

            $requestCommande = $bdd->prepare('SELECT * FROM commande WHERE id_commande = :id_commande');
            $requestCommande->bindParam(':id_commande', $_GET['commande'], PDO::PARAM_INT);

            try {
                $requestCommande->execute();
            } catch (PDOException $exception) {
                header('Location: ' . URL . 'errors/error500.php');
                exit();
            }

            if ($requestCommande->rowCount() == 0) {
                header('Location: ' . URL . 'errors/error404.php');
                exit();
            } else {
                $commande = $requestCommande->fetch(PDO::FETCH_ASSOC);
                extract($commande);
            }
        } else {
            header('Location: ' . URL . 'admin/membre_commande.php');
            exit();
        }

        if ($_POST) {

            extract($_POST);

            if (
                empty($adresse_livraison) || empty($livraison)
            ) {
                $error['champs'] = "Veuillez remplir les champs";
            }

            $livraison = htmlspecialchars($livraison);
            $etat = htmlspecialchars($etat);

            if (empty($error)) {
                $date_modification = new DateTime('now', new DateTimeZone('Europe/Paris'));
                $date_modification = $date_modification->format('Y-m-d H:i:s');

                $query = $bdd->prepare('UPDATE commande SET livraison = :livraison, adresse_livraison = :adresse_livraison, etat = :etat, update_at = :update_at WHERE id_commande = :id_commande');

                $query->bindParam(":id_commande", $_GET['commande'], PDO::PARAM_INT);
                $query->bindParam(":livraison", $livraison, PDO::PARAM_STR);
                $query->bindParam(":adresse_livraison", $adresse_livraison, PDO::PARAM_STR);
                $query->bindParam(":update_at", $date_modification, PDO::PARAM_STR);
                $query->bindParam(":etat", $etat, PDO::PARAM_STR);

                try {
                    $query->execute();

                    $validEdit = "La modification de la <b>Commande n° $_GET[commande]</b> a bien été effectué";
                    $_SESSION['content']['valid'] = $validEdit;

                    header('Location: ' . URL . 'admin/membre_commande.php?send=success');
                    exit();
                } catch (PDOException $exception) {
                    header("Location: " . URL . "admin/gestion_commande.php?action=edit&commande=$_GET[commande]&send=error");
                    exit();
                }
            }
        }
    }

    // Supression d'une commande

    if ($_GET['action'] == "suppression") {

        if (isset($_GET['commande']) && !empty($_GET['commande'])) {

            $query = $bdd->prepare('DELETE FROM commande WHERE id_commande = :id_commande');
            $query->bindParam(':id_commande', $_GET['commande'], PDO::PARAM_INT);

            try {
                $query->execute();

                if ($query->rowCount() == 0) {
                    header('Location: ' . URL . 'admin/membre_commande.php');
                    exit();
                }

                $validSupp = "La <b>commande nᵒ " . $_GET['commande'] . "</b> a bien été supprimée";
                $_SESSION['content']['valid'] = $validSupp;

                header('Location: ' . URL . 'admin/membre_commande.php?send=success');
                exit();
            } catch (PDOException $exception) {

                $errorSupp = "Erreur lors de la suppression";
                $_SESSION['content']['error'] = $errorSupp;

                header('Location: ' . URL . 'admin/membre_commande.php?send=error');
                exit();
            }
        } else {
            header('Location: ' . URL . 'admin/membre_commande.php');
            exit();
        }
    }
} else {
    header('Location: ' . URL . 'admin/membre_commande.php');
    exit();
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-membre">

        <div class="gestion-form">

            <h3>Modification de la Commande n° <?= $reference ?></h3>
            <hr>

            <?php echo ((isset($_GET['send']) && ($_GET['send'] == "error")) ? "<div class='message-error'>Erreur lors de la modification</div>" : "");
            ?>

            <?php if (isset($error['champs'])) : ?>
                <div class="message-error"><?= $error['champs'] ?></div>
            <?php endif ?>

            <form action="" method="POST">

                <div class="adresse-livraison">
                    <label for="adresse-livraison">Adresse de livraison :</label>
                    <input class="inputForm" type="text" name="adresse_livraison" id="adresse-livraison" value="<?= ($adresse_livraison) ??  '';  ?>">
                </div>

                <div class="livraison">
                    <label for="livraison">Mode de livraison :</label>
                    <input class="inputForm" type="text" name="livraison" id="livraison" value="<?= ($livraison) ??  '';  ?>">
                </div>

                <div class="etat">

                    <label for="etat">Etat :</label>
                    <select name="etat" id="etat">
                        <option value="annulé" <?= (isset($etat) && $etat == "annulé") ? 'selected' : '';  ?>>Annulé</option>
                        <option value="payé" <?= (isset($etat) && $etat == "payé") ? 'selected' : '';  ?>>Payé</option>
                        <option value="en cours de traitement" <?= (isset($etat) && $etat == "en cours de traitement") ? 'selected' : '';  ?>>En cours de traitement</option>
                        <option value="envoyé" <?= (isset($etat) && $etat == "envoyé") ? 'selected' : '';  ?>>Envoyé</option>
                        <option value="livré" <?= (isset($etat) && $etat == "livré") ? 'selected' : '';  ?>>Livré</option>
                    </select>

                </div>

                <div class="submit">
                    <input class="inputForm submit" type="submit" name="validation" value="Modifier">
                </div>

            </form>

        </div>

    </section>

</section>

<?php require_once('inc/footer.inc.php');  ?>