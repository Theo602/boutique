<?php require_once('../config/init.php'); ?>

<nav class="menu-dashboard">

    <ul class="menu">

        <li>
            <img src="../asset/img/dashboard/icone-dashboard.png" title="DashBoard" alt="DashBoard">
            <a href="index.php">Dashboard</a>
        </li>

        <li>
            <img src="../asset/img/dashboard/icone-profile.png" title="Profil" alt="Profil">
            <a href="profil_admin.php">Profil</a>
        </li>

        <li>
            <img src="../asset/img/dashboard/icone-boutique.png" title="Boutique" alt="Boutique">
            <a href="boutique.php">Boutique</a>
        </li>

        <li>
            <img src="../asset/img/dashboard/icone-produit.png" title="Produit" alt="Produit">
            <a href="gestion_produit.php?action=ajouter">Produit</a>
        </li>

        <li>
            <img src="../asset/img/dashboard/icone-order.png" title="Commande" alt="Commande">
            <a href="commande.php">Commande</a>
        </li>

        <li>
            <img src="../asset/img/dashboard/icone-client.png" title="Membre" alt="Membre">
            <a href="compte.php">Membre</a>
        </li>

        <li>
            <img src="../asset/img/dashboard/icone-deconnexion.png" title="Déconnexion" alt="Déconnexion">
            <a href="../deconnexion.php">Déconnexion</a>
        </li>

    </ul>

</nav>

<div class="btnDashboard">
    <div class="btnOpen"><i class="fas fa-bars"></i></div>
    <div class="btnClose"><i class="fas fa-times"></i></div>
</div>


<div class="modal-menu">
    <div class="modal-dashboard">
        <div class="modal-mobile"></div>
    </div>
</div>