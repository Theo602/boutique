<?php

require_once('config/init.php');


$pageTitle = 'Contact';
$pageMetaDesc = 'Retrouvez nos coordonnée et contactez nous avec nos formulaire de contact.';
$bodyId = CONTACT;

$error = [];

if ($_POST) {

    extract($_POST);

    if (
        empty($prenom) || empty($nom) || empty($email) || empty($sujet) || empty($message) || empty($captcha)
    ) {
        $error['champs'] = "Veuillez remplir les champs";
    }

    if ($captcha != $_SESSION["captcha_code"]) {
        $error['captcha'] = "Le captcha est incorrect";
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

    if (iconv_strlen($sujet) < 2 || iconv_strlen($sujet) > 100) {
        $error['sujet'] = "Le champs sujet doit être compris entre 50 et 100 caractères";
    }

    if (iconv_strlen($message) < 50 || iconv_strlen($message) > 500) {
        $error['message'] = "Le champs message doit être compris entre 50 et 500 caractères";
    }

    $prenom = htmlspecialchars($prenom);
    $nom = htmlspecialchars($nom);
    $email = htmlspecialchars($email);
    $sujet = htmlspecialchars($sujet);
    $message = htmlspecialchars($message);

    if (empty($error)) {

        /* On envoie un mail */

        $to = "contact@boutique-tcommerce.fr";
        $entete  = 'MIME-Version: 1.0' . "\r\n";
        $entete .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $entete .= 'From: formulaire-contact@boutique-tcommerce.fr' . "\r\n";
        $entete .= 'Reply-to: ' . $email;

        $message = '<html>
            <head>
            <title>Message de la page Contact de la boutique-tcommerce.fr</title>
            </head>
            <body>
                <h1>Message envoyé depuis la page Contact de la boutique-tcommerce.fr</h1>
            
                <p><strong>From: </strong>' .  $prenom . ' ' . $nom . '<br>
                <strong>Email: </strong>' . $email . '</p>
                <p><strong>Sujet: </strong> ' . $sujet . '</p>
                <p><strong>Message: </strong> ' . $message . '</p>
            
            
            </body>
            </html>';

        $send = mail($to, $sujet, $message, $entete);

        if ($send) {
            $valid = "Votre message a bien été envoyé";
            $_SESSION['content']['valid'] = $valid;
            header('Location: ' . URL . 'contact.php?send=success#return_message');
            exit();
        } else {
            $error = "Erreur lors de l'envoi de l'e-mail.";
            $_SESSION['content']['error'] = $error;
            header('Location: ' . URL . 'contact.php?send=error#return_message');
            exit();
        }
    }
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-contact">

    <figure class="image-contact">
        <img src="asset/img/slide/vêtement-ete.jpg" alt="Photo de la saison d'été">
    </figure>

</section>

<section class="section-2-contact">

    <h2>Coordonnées</h2>

    <hr>

    <div class="localisation">

        <div class="fiche-localisation">
            <h3>Informations</h3>
            <hr>
            <p>
                <i class="fas fa-user"></i>Boutique T-Commerce
            </p>
            <p>
                <i class="fas fa-location-dot"></i>
                Bayonne - Pays Basque
            </p>
            <a href="#"><i class="fa-brands fa-instagram"></i></i>Profil instagram</a>

        </div>

    </div>

</section>

<section class="section-3-contact" id="return_message">

    <h2>Nous contacter</h2>
    <hr class="contact-separate">

    <div id="send" class="background_formulaire">

        <div class="formulaire_text">

            <h3>Formulaire de contact</h3>

            <hr>

            <p>Vous avez besoin d'un renseignement ? une question ?
                <br>
                N'hésitez pas à nous laisser un message.
            </p>

            <p>Nous vous répondrai dans les plus brefs délais.</p>

        </div>

        <div class="formulaire_info">

            <form method="post" action="contact#return_message">

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

                <?php if (isset($error['champs'])) : ?>
                    <div class="message-error"><?= $error['champs'] ?></div>
                <?php endif ?>

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

                <div class="sujet">

                    <label for="sujet">Sujet :</label>
                    <input class="inputForm <?= isset($error['sujet']) ? 'border-error' : '' ?>" type="text" name="sujet" id="sujet" value="<?= ($sujet) ??  '';  ?>">

                    <div class="message-error-input active-message"></div>

                    <?php if (isset($error['sujet'])) : ?>
                        <div class="message-error-input"><?= $error['sujet'] ?></div>
                    <?php endif ?>

                </div>

                <div class="message">
                    <label for="message">Message :</label>
                    <textarea class="messageForm <?= isset($error['message']) ? 'border-error' : '' ?>" name="message" id="message" placeholder="Votre Message"><?= ($_POST['message']) ?? '' ?></textarea>

                    <div class="message-error-input active-message"></div>

                    <?php if (isset($error['message'])) : ?>
                        <div class="message-error-input"><?= $error['message'] ?></div>
                    <?php endif ?>

                </div>

                <div class="captcha-container">

                    <div class="captcha">
                        <input id="captcha" name="captcha" class="captchaForm <?= isset($error['captcha']) ? 'border-error' : '' ?>" type="text" placeholder="Entrez le captcha">
                        <img src="inc/captcha.inc.php" alt="Captcha" />

                    </div>

                    <div class="captchaErreur">

                        <?php if (isset($error['captcha'])) : ?>
                            <div class="message-error-input"><?= $error['captcha'] ?></div>
                        <?php endif ?>

                    </div>

                </div>

                <div class="submit">
                    <input class="inputForm submit" type="submit" name="valid" value="Envoyer">
                </div>

            </form>

        </div>

    </div>

</section>

<?php require_once('inc/footer.inc.php');  ?>