<?php

require_once('../config/init.php');

if (!userIsAdmin()) {
    header('Location: ../errors/error403.php');
}

$pageTitle = 'Gestion des membres';
$pageMetaDesc = 'Modifier - supprimer des membres';
$bodyId = ADMIN_MEMBRE;

$error = [];

if (!isset($_GET['action']) || empty($_GET['action'])) {
    header('Location: errors/error404.php');
    exit();
}

if ($_GET['action'] == "edit" || $_GET['action'] == "suppression") {

    // Modification du profil d'un membre

    if ($_GET['action'] == "edit") {

        if (isset($_GET['membre']) && !empty($_GET['membre'])) {

            $requestUser = $bdd->prepare('SELECT * FROM user WHERE id_membre = :id_membre');
            $requestUser->bindParam(':id_membre', $_GET['membre'], PDO::PARAM_INT);

            try {
                $requestUser->execute();
            } catch (PDOException $exception) {
                header('Location: ../errors/error500.php');
                exit();
            }

            if ($requestUser->rowCount() == 0) {
                header('Location: ../errors/error404.php');
                exit();
            } else {
                $user = $requestUser->fetch(PDO::FETCH_ASSOC);
                extract($user);
            }
        } else {
            header('Location: ../errors/error404.php');
            exit();
        }

        if ($_POST) {

            extract($_POST);

            if (
                empty($nom) || empty($prenom) || empty($email) || empty($ville) || empty($code_postal)
                || empty($adresse)
            ) {
                $error['champs'] = "Veuillez remplir les champs";
            }

            if (!preg_match("/^([A-zÀ-ú])*$/", $prenom) || (iconv_strlen($prenom) < 2 || iconv_strlen($prenom) > 20)) {
                $error['prenom'] = "Le champs prénom est incorrect (caractères interdits - doit être compris entre 2 et 20 caractères)";
            }

            if (!preg_match("/^([A-zÀ-ú])*$/", $nom) || (iconv_strlen($nom) < 2 || iconv_strlen($nom) > 20)) {
                $error['nom'] = "Le champs nom est incorrect (caractères interdits - doit être compris entre 2 et 20 caractères)";
            }

            if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
                $error['email'] = "L'adresse email est incorrect (caractères interdits)";
            }

            if (!preg_match("/^\d{5}$/", $code_postal) || (iconv_strlen($code_postal) != 5)) {
                $error['code_postal'] = "Le code postal est incorrect";
            }

            $nom = htmlspecialchars($nom);
            $prenom = htmlspecialchars($prenom);
            $email = htmlspecialchars($email);
            $civilite = htmlspecialchars($civilite);
            $ville = htmlspecialchars($ville);
            $code_postal = htmlspecialchars($code_postal);
            $adresse = htmlspecialchars($adresse);

            if (empty($error)) {

                $date_modification = new DateTime('now', new DateTimeZone('Europe/Paris'));
                $date_modification = $date_modification->format('Y-m-d H:i:s');

                $query = $bdd->prepare('UPDATE user SET nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, ville = :ville, code_postal = :code_postal, adresse = :adresse, status = :status, update_at = :update_at WHERE id_membre = :id_membre');

                $query->bindParam(":id_membre", $_GET['membre'], PDO::PARAM_INT);
                $query->bindParam(":nom", $nom, PDO::PARAM_STR);
                $query->bindParam(":prenom", $prenom, PDO::PARAM_STR);
                $query->bindParam(":email", $email, PDO::PARAM_STR);
                $query->bindParam(":civilite", $civilite, PDO::PARAM_STR);
                $query->bindParam(":ville", $ville, PDO::PARAM_STR);
                $query->bindParam(":code_postal", $code_postal, PDO::PARAM_INT);
                $query->bindParam(":adresse", $adresse, PDO::PARAM_STR);
                $query->bindParam(":status", $status, PDO::PARAM_INT);
                $query->bindParam(":update_at", $date_modification, PDO::PARAM_STR);

                try {
                    $query->execute();

                    $validEdit = "La modification de " . ucfirst($prenom) . " " . ucfirst($nom) . " (ID : " . $_GET['membre'] . ") a bien été effectué";
                    $_SESSION['content']['valid'] = $validEdit;

                    header('Location: compte.php?send=success#messageForm');
                    exit();
                } catch (PDOException $exception) {

                    header("Location: gestion_membre.php?action=edit&membre=$_GET[membre]&send=error#messageForm");
                    exit();
                }
            }
        }
    }

    // Supression du profil d'un membre

    if ($_GET['action'] == "suppression") {

        if (!isset($_GET['membre']) && empty($_GET['membre'])) {
            header('Location: ../errors/error404.php');
            exit();
        }

        $query = $bdd->prepare('DELETE FROM user WHERE id_membre = :id_membre');
        $query->bindParam(":id_membre", $_GET['membre'], PDO::PARAM_INT);

        try {
            $query->execute();

            $validSupp = "Le compte nᵒ " . $_GET['membre'] . " a bien été supprimé";
            $_SESSION['content']['valid'] = $validSupp;

            header('Location: compte.php?send=success#messageForm');
            exit();
        } catch (PDOException $exception) {

            $ErrorSupp = "Erreur lors de la suppression";
            $_SESSION['content']['error'] = $ErrorSupp;
            header('Location: compte.php?send=error#messageForm');
            exit();
        }
    }
} else {
    header('Location: ../errors/error404.php');
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

        <div class="gestion-form" id="messageForm">

            <h3>Modification du compte de <?= ucfirst($user['prenom']) . " " .
                                                ucfirst($user['nom']); ?></h3>
            <hr>

            <?php echo ((isset($_GET['send']) && ($_GET['send'] == "error")) ? "<div class='message-error'>Erreur lors de la modification</div>" : "");
            ?>

            <?php if (isset($error['champs'])) : ?>
                <div class="message-error"><?= $error['champs'] ?></div>
            <?php endif ?>

            <form id="messageForm" action="" method="POST">

                <div class="civilite">
                    <p class="civilite">Civilité :</p>
                    <input type="radio" name="civilite" value="homme" id="masculin" checked /> <label for="masculin">Homme</label>
                    <input type="radio" name="civilite" value="femme" id="feminin" /> <label for="feminin">Femme</label>
                </div>

                <div class="prenom">

                    <label for="prenom">Prenom :</label>
                    <input class="inputForm <?= isset($error['prenom']) ? 'border-error' : '' ?>" type="text" name="prenom" id="prenom" value="<?= ($prenom) ??  '';  ?>">

                    <?php if (isset($error['prenom'])) : ?>
                        <div class="message-error-input"><?= $error['prenom'] ?></div>
                    <?php endif ?>

                </div>

                <div class="nom">

                    <label for="nom">Nom :</label>
                    <input class="inputForm <?= isset($error['nom']) ? 'border-error' : '' ?>" type="text" name="nom" id="nom" value="<?= ($nom) ??  '';  ?>">

                    <?php if (isset($error['nom'])) : ?>
                        <div class="message-error-input"><?= $error['nom'] ?></div>
                    <?php endif ?>

                </div>

                <div class="email">

                    <label for="email">Email :</label>
                    <input class="inputForm <?= isset($error['email']) ? 'border-error' : '' ?>" type="email" name="email" id="email" value="<?= ($email) ??  '';  ?>">

                    <?php if (isset($error['email'])) : ?>
                        <div class="message-error-input"><?= $error['email'] ?></div>
                    <?php endif ?>

                </div>

                <div class="ville">
                    <label for="ville">Ville :</label>
                    <input class="inputForm" type="text" name="ville" id="ville" value="<?= ($ville) ??  '';  ?>">
                </div>

                <div class="code-postal">

                    <label for="code-postal">Code postal :</label>
                    <input class="inputForm <?= isset($error['code_postal']) ? 'border-error' : '' ?>" type="text" name="code_postal" id="code_postal" value="<?= ($code_postal) ??  '';  ?>">

                    <?php if (isset($error['code_postal'])) : ?>
                        <div class="message-error-input"><?= $error['code_postal'] ?></div>
                    <?php endif ?>

                </div>

                <div class="adresse">
                    <label for="adresse">Adresse :</label>
                    <input class="inputForm" type="text" name="adresse" id="adresse" value="<?= ($adresse) ??  '';  ?>">
                </div>

                <div class="status">

                    <label for="status">Status :</label>
                    <select name="status" id="status">
                        <option value="0" <?= (isset($status) && $status == 0) ? 'selected' : '';  ?>>Client</option>
                        <option value="1" <?= (isset($status) && $status == 1) ? 'selected' : '';  ?>>Administrateur</option>
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