<?php

require_once('config/init.php');


$pageTitle = 'Accueil';
$pageMetaDesc = 'Découvrez notre boutique de prêt-à-porter pour les jeunes adultes.';
$bodyId = HOME_PAGE;


/* Affichage des catégorie */

$requestCategorie = $bdd->prepare('SELECT DISTINCT categorie FROM produit');

try {
    $requestCategorie->execute();
} catch (PDOException $exception) {
    header('Location: errors/error500.php');
    exit();
}

$categories = $requestCategorie->fetchAll();

/* Affichage des derniers produits */

$requestProduit = $bdd->prepare('SELECT * FROM produit ORDER BY id_produit DESC LIMIT 0, 4');

try {
    $requestProduit->execute();
} catch (PDOException $exception) {
    header('Location: errors/error500.php');
    exit();
}

$produits = $requestProduit->fetchAll();


/* Gestion des newsletters */

if (isset($_POST['newsletter-validation'])) {

    extract($_POST);

    if (empty($email)) {
        $error['email'] = "Veuillez indiquer un email";
    }

    if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
        $error['email'] = "L'adresse email est incorrect (caractères interdits)";
    }

    // Vérification de l'existence de l'email 

    $emailFind = $bdd->prepare("SELECT * FROM newsletter WHERE email = :email");
    $emailFind->bindParam(':email', $email, PDO::PARAM_STR);

    try {
        $emailFind->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    if ($emailFind->rowCount() == 1) {
        $error['email'] = "L'email $email existe déjà, Veuillez en saisir un autre.";
    }

    $email = htmlspecialchars($email);

    if (empty($error)) {

        $date_creation = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $date_creation = $date_creation->format('Y-m-d H:i:s');

        $query =  $bdd->prepare("INSERT INTO newsletter(email, created_at)
        VALUES(:email, :created_at)");

        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":created_at", $date_creation, PDO::PARAM_STR);

        try {
            $query->execute();
            $validNewletter = 'Votre inscription a bien été prise en compte.';
            $_SESSION['content']['valid'] = $validNewletter;
            header('Location: index.php#return_newsletter');
            exit();
        } catch (PDOException $exception) {
            $errorNewletter = "Erreur lors de l'inscription";
        }
    }
}



require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-accueil">

    <h2>Bienvenue chez T-commerce</h2>

    <?php require_once('inc/carrousel.inc.php');  ?>

</section>

<section class="section-2-accueil">

    <h2>Nos catégories</h2>
    <hr>

    <div class="block-categories">

        <?php if (!empty($categories)) : ?>

            <?php foreach ($categories as $categorie) : ?>

                <div class="fiche-categorie">
                    <figure>
                        <img src="<?= IMG_CATEGORIE_DIR . $categorie['categorie'] . ".jpg" ?>" alt="Catégorie <?= $categorie['categorie'] ?>">
                    </figure>
                    <h3 class="titre_categorie"><?= ucfirst($categorie['categorie']) ?></h3>
                    <a href="<?= "boutique.php?categorie=" . $categorie['categorie'] ?>">Voir les produits</a>
                </div>

            <?php endforeach; ?>

        <?php else : ?>

            <div class="message-info-categorie">

                <p>Aucune catégorie pour le moment</p>

            </div>

        <?php endif ?>

    </div>

</section>

<section class="section-3-accueil">

    <h2>Nos derniers produits</h2>
    <hr>

    <div class="block-produit-accueil">

        <?php if (!empty($produits)) : ?>

            <?php foreach ($produits as $produit) : ?>

                <div class="fiche-produit-accueil">
                    <figure>
                        <img src="<?= $produit['photo'] ?>" alt="Catégorie <?= $produit['titre'] ?>">
                    </figure>
                    <h3 class="titre_produit_accueil"><?= ucfirst($produit['titre']) ?></h3>
                    <p><?= ucfirst($produit['public']) ?></p>
                    <a href="<?= "fiche-produit.php?id_produit=" . $produit['id_produit'] ?>">Voir le produit</a>
                </div>

            <?php endforeach; ?>

        <?php else : ?>

            <div class="message-info-produit">

                <p>Aucun produit pour le moment</p>

            </div>

        <?php endif ?>

    </div>

</section>

<section class="section-4-accueil" id="return_newsletter">

    <h2>Newsletter</h2>
    <hr>

    <p>
        Vous souhaitez recevoir notre actualité ?<br>
        Inscrivez vous à notre newsletter.
    </p>

    <form method="post" action="index#return_newsletter">

        <?php if (isset($_SESSION['content']['valid'])) : ?>
            <div class='message-succes-newsletter'> <?= $_SESSION['content']['valid']; ?></div>
        <?php endif;
        unset($_SESSION['content']['valid']); ?>

        <?php if (isset($errorNewletter)) : ?>
            <div class="message-error-newsletter"><?= $errorNewletter; ?></div>
        <?php endif ?>

        <div class="newsletter-email">
            <input class="input-newsletter <?= isset($error['email']) ? 'border-error' : '' ?>" type="email" name="email" id="email" placeholder="Entrez votre adresse email" value="<?= ($email) ??  '';  ?>">

            <?php if (isset($error['email'])) : ?>
                <div class="message-error-newsletter"><?= $error['email'] ?></div>
            <?php endif ?>

        </div>

        <div class="newsletter-validation">
            <input class="submit-newsletter" type="submit" name="newsletter-validation" id="newsletter-validation" value="S'inscrire">
        </div>

    </form>


</section>

<?php require_once('inc/footer.inc.php');  ?>