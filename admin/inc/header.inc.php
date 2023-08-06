<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $pageMetaDesc ?>">
    <title><?= $pageTitle ?></title>

    <!-- Css -->

    <link rel="stylesheet" href="../asset/css/style_back.css">

    <!-- CDN Font-awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

</head>

<body id="<?= $bodyId ?>">

    <header>
        <div class="back-header-top">

            <div class="header-return">
                <p>
                    <a href="../index.php">Retour sur le site</a>
                </p>
            </div>

            <h1>DashBoard</h1>

            <div class="icone-header">

                <a href="index.php">
                    <img src="../asset/img/dashboard/icone-dashboard.png" title="DashBoard" alt="DashBoard">
                </a>

                <a href="admin_compte.php">
                    <img src="../asset/img/dashboard/icone-client.png" title="Mon compte" alt="Mon compte">
                </a>

                <a href="boutique.php">
                    <img src="../asset/img/dashboard/icone-boutique.png" title="Boutique" alt="Boutique">
                </a>

                <a href="membre_commande.php">
                    <img src="../asset/img/dashboard/icone-order.png" title="Commande membres" alt="Commande membres">
                </a>

                <a href="membre_compte.php">
                    <img src="../asset/img/dashboard/icone-membre.png" title="Membre" alt="Membre">
                </a>

                <a href="../deconnexion.php">
                    <img src="../asset/img/dashboard/icone-deconnexion.png" title="Déconnexion" alt="Déconnexion">
                </a>

            </div>

        </div>
    </header>

    <main class="container">

        <div class="dashboard-container">