<?php

require_once('config/init.php');

$pageTitle = 'Connexion';
$pageMetaDesc = 'Connectez vous à votre espace client';
$bodyId = SIGN_UP;


if (userConnected()) {
    header('Location: profil.php');
    exit();
}

$erreur = [];

if ($_POST) {

    if (!empty($_POST['email']) && !empty($_POST['password'])) {

        extract($_POST);

        if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
            $error['email'] = "L'adresse email est incorrect (caractères interdits)";
        }

        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));

        $requestConnect = $bdd->prepare('SELECT * FROM user WHERE email = :email ');
        $requestConnect->bindParam(":email", $email, PDO::PARAM_STR);

        try {
            $requestConnect->execute();
        } catch (PDOException $exception) {
            header('Location: errors/error500.php');
            exit();
        }

        if ($requestConnect->rowCount() >= 1) {

            $user = $requestConnect->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user']['id_membre'] = $user['id_membre'];
                $_SESSION['user']['status'] = $user['status'];

                if ($user['status'] == 1) {
                    header('Location: admin/profil_admin.php');
                    exit();
                } else {
                    header('Location: profil.php');
                    exit();
                }
            } else {
                $error['identification'] = 'Mot de passe incorrect';
            }
        } else {
            $error['email'] = "L'email n'existe pas";
        }
    } else {
        $error['champs'] = "L'email et le mot de passe sont incorrect";
    }
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-connexion">

    <div class="connexion-form" id="messageForm">

        <?php echo ((isset($_GET['send']) && ($_GET['send'] == "success")) ? "<div class='message-succes'>Vous êtes inscrit avec succès.</div>" : ""); ?>

        <h3>Connexion</h3>
        <hr>

        <p class="connect">Entrez vos identifiants pour vous connectez</p>

        <?php if (isset($error['identification'])) : ?>
            <div class="message-error"><?= $error['identification'] ?></div>
        <?php endif ?>

        <?php if (isset($error['champs'])) : ?>
            <div class="message-error"><?= $error['champs'] ?></div>
        <?php endif ?>

        <?php if (isset($error['email'])) : ?>
            <div class="message-error"><?= $error['email'] ?></div>
        <?php endif ?>

        <form action="" method="POST">

            <div class="email">
                <label for="email">Email :</label>
                <input class="inputForm" type="email" name="email" id="email" value="<?= ($_POST['email']) ??  '';  ?>">
            </div>

            <div class="password">
                <label for="password">Mot de passe :</label>
                <input class="inputForm" type="password" name="password" id="password">
            </div>

            <div class="submit">
                <input class="inputForm submit" type="submit" name="valid" value="Connexion">
            </div>

        </form>

        <p class="connect">Pas encore inscrit? suivez ce lien pour vous inscrire <a href="inscription.php"> ICI </a></p>


    </div>

</section>

<?php require_once('inc/footer.inc.php');  ?>