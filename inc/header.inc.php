<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $pageMetaDesc ?>">
    <title><?= $pageTitle ?></title>

    <!-- Css -->

    <link rel="stylesheet" href="asset/css/style_front.css">

    <?php if (isset($bodyId)) : ?>

        <?php if ($bodyId === MEMBER_COMPTE  || $bodyId === MEMBER_PROFILE || $bodyId === MEMBER_COMMANDE) : ?>
            <link rel="stylesheet" href="asset/css/style_front_profil.css">
        <?php endif ?>

    <?php endif ?>

    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- CDN Font-awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

</head>

<body id="<?= $bodyId ?>">

    <header>

        <div class="header-top">

            <h1>Boutique T-commerce</h1>

        </div>

        <nav class="menu-navigation">

            <ul class="menu">

                <li><a href="<?= URL; ?>index.php" aria-current="page">Accueil</a></li>

                <?php if (userIsAdmin()) : ?>

                    <li><a href="<?= URL; ?>boutique.php">Boutique</a></li>
                    <li><a href="<?= URL; ?>admin">Dashboard</a></li>
                    <li>
                        <a class="link-shop" href="<?= URL; ?>panier.php">Panier
                            <img class="image-shop" src="asset/img/shop/icone-panier.png" alt="icone panier">
                            <span class="count-shop">
                                <?= (!empty(nbreProduitPanier())) ? nbreProduitPanier() : 0 ?>
                            </span>
                        </a>
                    </li>
                    <li><a href="<?= URL; ?>deconnexion.php">Deconnexion</a></li>


                <?php elseif (userConnected() && $_SESSION['user']['status'] == 0) : ?>

                    <li><a href="<?= URL; ?>boutique.php">Boutique</a></li>
                    <li><a href="<?= URL; ?>compte.php">Mon Compte</a></li>
                    <li>
                        <a class="link-shop" href="<?= URL; ?>panier.php">Panier
                            <img class="image-shop" src="asset/img/shop/icone-panier.png" alt="icone panier">
                            <span class="count-shop">
                                <?= (!empty(nbreProduitPanier())) ? nbreProduitPanier() : 0 ?>
                            </span>
                        </a>
                    </li>
                    <li><a href="<?= URL; ?>deconnexion.php">Deconnexion</a></li>

                <?php else : ?>

                    <li><a href="<?= URL; ?>boutique.php">Boutique</a></li>
                    <li><a href="<?= URL; ?>connexion.php">Compte</a></li>
                    <li>
                        <a class="link-shop" href="<?= URL; ?>panier.php">Panier
                            <img class="image-shop" src="asset/img/shop/icone-panier.png" alt="icone panier">
                            <span class="count-shop">
                                <?= (!empty(nbreProduitPanier())) ? nbreProduitPanier() : 0 ?>
                            </span>
                        </a>
                    </li>

                    <li><a href="<?= URL; ?>contact.php">Contact</a></li>

                <?php endif ?>

            </ul>

        </nav>

        <div class="header-panier-mobile">
            <a class="link-shop" href="<?= URL; ?>panier.php">
                <img class="image-shop-mobile" src="asset/img/shop/icone-panier.png" alt="icone panier">
                <span class="count-shop-mobile">
                    <?= (!empty(nbreProduitPanier())) ? nbreProduitPanier() : 0 ?>
                </span>
            </a>
        </div>

        <div class="btnOpen"><i class="fas fa-bars"></i></div>

    </header>

    <?php require_once('barre_de_recherche.inc.php');  ?>

    <div class="modal-menu">
        <div class="modal-navigation">
            <div class="btnClose"><i class="fas fa-times"></i></div>
            <div class="modal-mobile"></div>
        </div>
    </div>

    <main class="container">