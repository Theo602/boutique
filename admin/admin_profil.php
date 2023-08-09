<?php

require_once('../config/init.php');


$pageTitle = 'Espace Admin -Profil';
$pageMetaDesc = 'Bienvenue sur votre profil - Modifier votre profil';
$bodyId = ADMIN_PROFIL;


if (!userIsAdmin()) {
    header('Location: ' . URL . 'errors/error403.php');
}


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

$date_modification = new DateTime('now', new DateTimeZone('Europe/Paris'));
$date_modification = $date_modification->format('Y-m-d H:i:s');

/* Modification du profil */

if (isset($_POST['btn_modifier_profil'])) {

    extract($_POST);
    if (!preg_match("/^([A-zÀ-ú])*$/", $prenom) || (iconv_strlen($prenom) < 2 || iconv_strlen($prenom) > 20)) {
        $error['prenom'] = "Le champs prénom est incorrect (caractères interdits - doit être compris entre 2 et 20 caractères)";
    }

    if (!preg_match("/^([A-zÀ-ú])*$/", $nom) || (iconv_strlen($nom) < 2 || iconv_strlen($nom) > 20)) {
        $error['nom'] = "Le champs nom est incorrect (caractères interdits - doit être compris entre 2 et 20 caractères)";
    }

    if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
        $error['email'] = "L'adresse email est incorrect (caractères interdits)";
    }

    if (!preg_match("/^([+]{1}[1-9]{2,3}[ .-]?)?[0-9]{1,3}([ .-]?[0-9]{2,3}){3,6}$/", $telephone)) {
        $error['telephone'] = "Le numero de téléphone est incorrect";
    }

    $nom = htmlspecialchars($nom);
    $prenom = htmlspecialchars($prenom);
    $email = htmlspecialchars($email);
    $telephone = htmlspecialchars($telephone);
    $civilite = htmlspecialchars($civilite);

    if (empty($error)) {

        $query = $bdd->prepare('UPDATE user SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, civilite = :civilite, update_at = :update_at WHERE id_membre = :id_membre');

        $query->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);
        $query->bindParam(":nom", $nom, PDO::PARAM_STR);
        $query->bindParam(":prenom", $prenom, PDO::PARAM_STR);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":telephone", $telephone, PDO::PARAM_STR);
        $query->bindParam(":civilite", $civilite, PDO::PARAM_STR);
        $query->bindParam(":update_at", $date_modification, PDO::PARAM_STR);

        try {
            $query->execute();
            $valid = 'Le profil a été modifié';
            $_SESSION['content']['validProfil'] = $valid;
            header('Location: ' . URL . 'admin/admin_profil.php#messageProfil');
            exit();
        } catch (PDOException $exception) {
            $errorMotif = 'Erreur lors de la modification';
        }
    } else {
        $_SESSION['champs']['error'] = $error;
        header('Location: ' . URL . 'admin/admin_profil.php#messageProfil');
        exit();
    }
}

/* Modification du carnet d'adresse */

if (isset($_POST['btn_modifier_adresse'])) {

    extract($_POST);

    if ((iconv_strlen($adresse) < 2 || iconv_strlen($adresse) > 100)) {
        $error['adresse'] = "Le champs adresse est incorrect (doit être compris entre 2 et 100 caractères)";
    }

    if ((iconv_strlen($ville) < 2 || iconv_strlen($ville) > 100)) {
        $error['ville'] = "Le champs ville est incorrect (doit être compris entre 2 et 100 caractères)";
    }

    if ((iconv_strlen($pays) < 2 || iconv_strlen($pays) > 20)) {
        $error['pays'] = "Le champs pays est incorrect (doit être compris entre 2 et 100 caractères)";
    }

    if (!preg_match("/^\d{5}$/", $code_postal) || (iconv_strlen($code_postal) != 5)) {
        $error['code_postal'] = "Le code postal est incorrect";
    }

    $ville = htmlspecialchars($ville);
    $code_postal = htmlspecialchars($code_postal);
    $adresse = htmlspecialchars($adresse);
    $pays = htmlspecialchars($pays);

    if (empty($error)) {

        $query = $bdd->prepare('UPDATE user SET ville = :ville, code_postal = :code_postal, adresse = :adresse, pays = :pays, update_at = :update_at WHERE id_membre = :id_membre');

        $query->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);
        $query->bindParam(":ville", $ville, PDO::PARAM_STR);
        $query->bindParam(":code_postal", $code_postal, PDO::PARAM_INT);
        $query->bindParam(":adresse", $adresse, PDO::PARAM_STR);
        $query->bindParam(":pays", $pays, PDO::PARAM_STR);
        $query->bindParam(":update_at", $date_modification, PDO::PARAM_STR);

        try {
            $query->execute();
            $valid = "L'adresse a été modifiée";
            $_SESSION['content']['validAdresse'] = $valid;
            header('Location: ' . URL . 'admin/admin_profil.php#messageAdresse');
            exit();
        } catch (PDOException $exception) {
            $errorMotif = 'Erreur lors de la modification';
        }
    } else {
        $_SESSION['champs']['error'] = $error;
        header('Location: ' . URL . 'admin/admin_profil.php#messageAdresse');
        exit();
    }
}

/* Modification du mot de passe */

if (isset($_POST['btn_modifier_password'])) {

    extract($_POST);

    if (empty($passwordActuel) ||  empty($password) ||  empty($confirmPassword)) {
        $error['champsErrors'] = "Veuillez remplir les champs";
    }

    if (password_verify($passwordActuel, $user['password'])) {
        if ($password != $confirmPassword) {
            $error['password'] = "Les mots de passes ne sont pas identiques";
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }
    } else {
        $error['passwordActuel'] = "Le mot de passe actuel est incorrect";
    }

    $password = htmlspecialchars($password);

    if (empty($error)) {

        $query = $bdd->prepare('UPDATE user SET password = :password, update_at = :update_at WHERE id_membre = :id_membre');

        $query->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);
        $query->bindParam(":password", $password, PDO::PARAM_STR);
        $query->bindParam(":update_at", $date_modification, PDO::PARAM_STR);

        try {
            $query->execute();
            $valid = "Le mot de passe a été modifiée";
            $_SESSION['content']['validPassword'] = $valid;
            header('Location: ' . URL . 'admin/admin_profil.php#messagePassword');
            exit();
        } catch (PDOException $exception) {
            $errorMotif = 'Erreur lors de la modification';
        }
    } else {
        $_SESSION['champs']['error'] = $error;
        header('Location: ' . URL . 'admin/admin_profil.php#messagePassword');
        exit();
    }
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-profil" id="messageProfil">

        <div class="espace-profil">

            <figure>
                <img src="../asset/img/profil/avatar-profile.png" id="Avatar profile.png" alt="Photo de profile">
            </figure>

            <div class="fiche-profil">

                <p><?= ucfirst($prenom) . " " . ucfirst($nom); ?></p>
                <p>Status : <?= ($status == 0) ? 'Client' : 'Administrateur'; ?></p>
                <p><?= $email; ?></p>
                <p><?= $telephone; ?></p>

            </div>

        </div>

        <div class="detail-profil">

            <h3>Détail du profil</h3>
            <hr>

            <?php if (isset($_SESSION['content']['validProfil'])) : ?>
                <div class='message-succes'> <?= $_SESSION['content']['validProfil']; ?></div>
            <?php endif;
            unset($_SESSION['content']['validProfil']); ?>

            <?php if (isset($errorMotif)) : ?>
                <div class='message-error-profil'> <?= $errorMotif; ?></div>
            <?php endif; ?>

            <form action="" method="POST">

                <input class="input-profil" type="hidden" name="id_membre" id="id_membre" value="<?php echo ($id_membre) ??  '';  ?>">

                <div class="civilite-profil">
                    <input class="input-civilite-profil" type="radio" name="civilite" value="homme" id="homme" <?= (isset($civilite) && $civilite == "homme") ? 'checked' : ''; ?> /> <label for="homme">Homme</label>
                    <input class="input-civilite-profil" type="radio" name="civilite" value="femme" id="femme" <?= (isset($civilite) && $civilite == "femme") ? 'checked' : ''; ?> /> <label for="femme">Femme</label>
                </div>

                <div class="prenom">

                    <input class="input-profil <?= isset($_SESSION['champs']['error']['prenom']) ? 'border-error-profil' : '' ?>" type="text" name="prenom" id="prenom" value="<?= ($prenom) ??  '';  ?>">

                    <?php if (isset($_SESSION['champs']['error']['prenom'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['prenom']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['prenom']); ?>

                </div>

                <div class="nom">

                    <input class="input-profil <?= isset($_SESSION['champs']['error']['nom']) ? 'border-error-profil' : '' ?>" type="text" name="nom" id="nom" value="<?= ($nom) ??  '';  ?>">

                    <?php if (isset($_SESSION['champs']['error']['nom'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['nom']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['nom']); ?>

                </div>

                <div class="email">

                    <input class="input-profil <?= isset($_SESSION['champs']['error']['email']) ? 'border-error-profil' : '' ?>" type="email" name="email" id="email" value="<?= ($email) ??  '';  ?>">

                    <?php if (isset($_SESSION['champs']['error']['email'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['email']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['email']); ?>

                </div>

                <div class="telephone">

                    <input class="input-profil <?= isset($_SESSION['champs']['error']['telephone']) ? 'border-error-profil' : '' ?>" type="text" name="telephone" id="telephone" value="<?= ($telephone) ??  '';  ?>">

                    <?php if (isset($_SESSION['champs']['error']['telephone'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['telephone']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['telephone']); ?>

                </div>

                <div class="submit-profil-block">
                    <input class="input-profil submit-profil" type="submit" name="btn_modifier_profil" value="Modifier">
                </div>

            </form>

        </div>

    </section>

    <section class="section-2-profil" id="messageAdresse">

        <div class="espace-adresse">

            <h3>Carnet d'adresse</h3>
            <hr>

            <p><?= $adresse; ?></p>
            <p><?= $code_postal . " " . ucfirst($ville); ?></p>
            <p><?= ucfirst($pays); ?></p>
            <p><?= $telephone; ?></p>

        </div>

        <div class="detail-adresse">

            <h3>Détail d'adresse</h3>
            <hr>

            <?php if (isset($_SESSION['content']['validAdresse'])) : ?>
                <div class='message-succes'> <?= $_SESSION['content']['validAdresse']; ?></div>
            <?php endif;
            unset($_SESSION['content']['validAdresse']); ?>

            <?php if (isset($errorMotif)) : ?>
                <div class='message-error-profil'> <?= $errorMotif; ?></div>
            <?php endif; ?>

            <form action="" method="POST">

                <input class="input-profil" type="hidden" name="id_membre" id="id_membre" value="<?php echo ($id_membre) ??  '';  ?>">

                <div class="ville">

                    <input class="input-profil <?= isset($_SESSION['champs']['error']['ville']) ? 'border-error-profil' : '' ?>" type="text" name="ville" id="ville" value="<?= ($ville) ??  '';  ?>">

                    <?php if (isset($_SESSION['champs']['error']['ville'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['ville']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['ville']); ?>

                </div>

                <div class="code-postal">

                    <input class="input-profil <?= isset($_SESSION['champs']['error']['code_postal']) ? 'border-error-profil' : '' ?>" type="text" name="code_postal" id="code_postal" value="<?= ($code_postal) ??  '';  ?>">

                    <?php if (isset($_SESSION['champs']['error']['code_postal'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['code_postal']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['code_postal']); ?>

                </div>

                <div class="adresse">

                    <input class="input-profil <?= isset($_SESSION['champs']['error']['adresse']) ? 'border-error-profil' : '' ?>" type="text" name="adresse" id="adresse" value="<?= ($adresse) ??  '';  ?>">

                    <?php if (isset($_SESSION['champs']['error']['adresse'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['adresse']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['adresse']); ?>

                </div>

                <div class="pays">

                    <input class="input-profil <?= isset($_SESSION['champs']['error']['pays']) ? 'border-error-profil' : '' ?>" type="text" name="pays" id="pays" value="<?= ($pays) ??  '';  ?>">

                    <?php if (isset($_SESSION['champs']['error']['pays'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['pays']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['pays']); ?>

                </div>

                <div class="submit-profil-block">
                    <input class="input-profil submit-profil" type="submit" name="btn_modifier_adresse" value="Modifier">
                </div>

            </form>

        </div>

    </section>

    <section class="section-3-profil" id="messagePassword">

        <div class="espace-password">

            <h3>Changer le mot de passe</h3>
            <hr>

            <?php if (isset($_SESSION['content']['validPassword'])) : ?>
                <div class='message-succes'> <?= $_SESSION['content']['validPassword']; ?></div>
            <?php endif;
            unset($_SESSION['content']['validPassword']); ?>

            <?php if (isset($_SESSION['champs']['error']['champsErrors'])) : ?>
                <div class='message-error'> <?= $_SESSION['champs']['error']['champsErrors']; ?></div>
            <?php endif;
            unset($_SESSION['champs']['error']['champsErrors']); ?>

            <?php if (isset($errorMotif)) : ?>
                <div class='message-error-profil'> <?= $errorMotif; ?></div>
            <?php endif; ?>

            <form action="" method="POST">

                <input class="input-profil" type="hidden" name="id_membre" id="id_membre" value="<?php echo ($id_membre) ??  '';  ?>">

                <div class="password-actuel">

                    <label class="password-profil" for="passwordActuel">Mot de passe actuel :</label>
                    <input class="input-profil <?= isset($_SESSION['champs']['error']['passwordActuel']) ? 'border-error-profil' : '' ?>" type="password" name="passwordActuel" id="passwordActuel">

                    <?php if (isset($_SESSION['champs']['error']['passwordActuel'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['passwordActuel']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['passwordActuel']); ?>

                </div>

                <div class="password">

                    <label class="password-profil" for="password">Mot de passe :</label>
                    <input class="input-profil <?= isset($_SESSION['champs']['error']['password']) ? 'border-error-profil' : '' ?>" type="password" name="password" id="password">

                    <?php if (isset($_SESSION['champs']['error']['password'])) : ?>
                        <div class='message-error'> <?= $_SESSION['champs']['error']['password']; ?></div>
                    <?php endif;
                    unset($_SESSION['champs']['error']['password']); ?>

                </div>

                <div class="password-confirm">
                    <label class="password-profil" for="confirmPassword">Confirmé le mot de passe :</label>
                    <input class="input-profil <?= isset($_SESSION['champs']['error']['password']) ? 'border-error-profil' : '' ?>" class="inputForm" type="password" name="confirmPassword" id="confirmPassword">
                </div>

                <div class="submit-profil-block">
                    <input class="input-profil submit-profil" type="submit" name="btn_modifier_password" value="Modifier">
                </div>

            </form>

        </div>

    </section>

</section>

<?php require_once('inc/footer.inc.php');  ?>