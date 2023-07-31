<?php

require_once('config/init.php');

$pageTitle = 'Inscription';
$pageMetaDesc = 'Inscrivez vous sur notre boutique de e-commerce';
$bodyId = SIGN_IN;

if (userConnected()) {
    header('Location: profil.php');
    exit();
}

// Création du profil d'un membre

$error = [];

if ($_POST) {

    extract($_POST);

    if (
        empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirmPassword) || empty($ville) || empty($code_postal)
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

    // Vérifier si l'email' est disponible 

    $emailFind = $bdd->query("SELECT * FROM user WHERE email = '$email'");

    if ($emailFind->rowCount() >= 1) {
        $error['email'] = "L'email existe déjà";
    }

    // Vérifier si les mots de passes sont identiques 

    if ($password != $confirmPassword) {
        $error['password'] = "Les mots de passes ne sont pas identiques";
    }

    if (!preg_match("/^\d{5}$/", $code_postal) || (iconv_strlen($code_postal) != 5)) {
        $error['code_postal'] = "Le code postal est incorrect";
    }

    $password = htmlspecialchars($password);
    $nom = htmlspecialchars($nom);
    $prenom = htmlspecialchars($prenom);
    $email = htmlspecialchars($email);
    $civilite = htmlspecialchars($civilite);
    $ville = htmlspecialchars($ville);
    $code_postal = htmlspecialchars($code_postal);
    $adresse = htmlspecialchars($adresse);

    $password = password_hash($password, PASSWORD_DEFAULT);

    if (empty($error)) {

        $status = 0;

        $date_creation = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $date_creation = $date_creation->format('Y-m-d H:i:s');

        $query =  $bdd->prepare("INSERT INTO user(password, nom, prenom, email, civilite, ville, code_postal, adresse, status, created_at)
        VALUES(:password, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, :status, :created_at)");

        $query->bindParam(":password", $password, PDO::PARAM_STR);
        $query->bindParam(":nom", $nom, PDO::PARAM_STR);
        $query->bindParam(":prenom", $prenom, PDO::PARAM_STR);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":civilite", $civilite, PDO::PARAM_STR);
        $query->bindParam(":ville", $ville, PDO::PARAM_STR);
        $query->bindParam(":code_postal", $code_postal, PDO::PARAM_INT);
        $query->bindParam(":adresse", $adresse, PDO::PARAM_STR);
        $query->bindParam(":status", $status, PDO::PARAM_INT);
        $query->bindParam(":created_at", $date_creation, PDO::PARAM_STR);

        try {
            $query->execute();
            header('Location: connexion.php?send=success#messageForm');
            exit();
        } catch (PDOException $exception) {
            header('Location: inscription.php?send=error#messageForm');
            exit();
        }
    }
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-inscription">

    <div class="inscription-form" id="messageForm">

        <h3>Inscription</h3>
        <hr>

        <p class="connect">Remplissez les informations pour vous inscrire sur le site.</p>

        <form id="messageForm" action="" method="POST">

            <?php echo ((isset($_GET['send']) && ($_GET['send'] == "error")) ? "<div class='message-error'>Erreur lors de l'inscription.</div>" : ""); ?>

            <?php if (isset($error['champs'])) : ?>
                <div class="message-error"><?= $error['champs'] ?></div>
            <?php endif ?>

            <div class="civilite">
                <p class="civilite">Civilité :</p>
                <input type="radio" name="civilite" value="homme" id="masculin" checked /> <label for="masculin">Homme</label>
                <input type="radio" name="civilite" value="femme" id="feminin" /> <label for="feminin">Femme</label>
            </div>

            <div class="prenom">

                <label for="prenom">Prenom :</label>
                <input class="inputForm <?= isset($error['prenom']) ? 'border-error' : '' ?>" type="text" name="prenom" id="prenom" value="<?= ($prenom) ??  '';  ?>">

                <div class="message-error-input active-message"></div>

                <?php if (isset($error['prenom'])) : ?>
                    <div class="message-error-input"><?= $error['prenom'] ?></div>
                <?php endif ?>

            </div>

            <div class="nom">

                <label for="nom">Nom :</label>
                <input class="inputForm <?= isset($error['nom']) ? 'border-error' : '' ?>" type="text" name="nom" id="nom" value="<?= ($nom) ??  '';  ?>">

                <div class="message-error-input active-message"></div>

                <?php if (isset($error['nom'])) : ?>
                    <div class="message-error-input"><?= $error['nom'] ?></div>
                <?php endif ?>

            </div>

            <div class="email">

                <label for="email">Email :</label>
                <input class="inputForm <?= isset($error['email']) ? 'border-error' : '' ?>" type="email" name="email" id="email" value="<?= ($email) ??  '';  ?>">

                <div class="message-error-input active-message"></div>

                <?php if (isset($error['email'])) : ?>
                    <div class="message-error-input"><?= $error['email'] ?></div>
                <?php endif ?>

            </div>

            <div class="password">

                <label for="password">Mot de passe :</label>
                <input class="inputForm <?= isset($error['password']) ? 'border-error' : '' ?>" type="password" name="password" id="password">

                <div class="message-error-input active-message"></div>

                <?php if (isset($error['password'])) : ?>
                    <div class="message-error-input"><?= $error['password'] ?></div>
                <?php endif ?>

            </div>

            <div class="confirmPassword">
                <label for="confirmPassword">Confirmé le mot de passe :</label>
                <input class="inputForm <?= isset($error['password']) ? 'border-error' : '' ?>" class="inputForm" type="password" name="confirmPassword" id="confirmPassword">
                <div class="message-error-input active-message"></div>
            </div>

            <div class="ville">
                <label for="ville">Ville :</label>
                <input class="inputForm" type="text" name="ville" id="ville" value="<?= ($ville) ??  '';  ?>">
                <div class="message-error-input active-message"></div>
            </div>

            <div class="code-postal">

                <label for="code-postal">Code postal :</label>
                <input class="inputForm <?= isset($error['code_postal']) ? 'border-error' : '' ?>" type="text" name="code_postal" id="code_postal" value="<?= ($code_postal) ??  '';  ?>">

                <div class="message-error-input active-message"></div>

                <?php if (isset($error['code_postal'])) : ?>
                    <div class="message-error-input"><?= $error['code_postal'] ?></div>
                <?php endif ?>

            </div>

            <div class="adresse">
                <label for="adresse">Adresse :</label>
                <input class="inputForm" type="text" name="adresse" id="adresse" value="<?= ($adresse) ??  '';  ?>">
                <div class="message-error-input active-message"></div>
            </div>

            <div class="submit">
                <input class="inputForm submit" type="submit" name="validation" value="Inscription">
            </div>

        </form>

        <p class="connect">Suivez ce lien pour vous connecter <a href="connexion.php"> ICI </a></p>

    </div>

</section>

<?php require_once('inc/footer.inc.php');  ?>