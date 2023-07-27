<?php

require_once('config/init.php');

$pageTitle = 'Espace Client';
$pageMetaDesc = 'Bienvenue sur votre espace client.';
$bodyId = MEMBER_PROFILE;


if (userConnected()) {

    $id_membre = $_SESSION['user']['id_membre'];

    $query = $bdd->prepare('SELECT * FROM user WHERE id_membre = :id_membre ');
    $query->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);

    try {
        $query->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    $user = $query->fetch(PDO::FETCH_ASSOC);
} else {
    header('Location: connexion.php');
    exit();
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-profil">

    <h2>Bienvenue sur votre profil <?= ucfirst($user['prenom']); ?></h2>

    <div class="profil-information">

        <h3>Information de votre profil</h3>
        <hr>

        <p>Prenom : <?= ucfirst($user['prenom']); ?></p>
        <p>Nom : <?= ucfirst($user['nom']); ?></p>
        <p>Email : <?= ucfirst($user['email']); ?></p>
        <p>Civilite : <?= ucfirst($user['civilite']); ?></p>
        <p>Adresse : <?= ucfirst($user['adresse']); ?></p>
        <p>Ville : <?= ucfirst($user['ville']); ?></p>
        <p>Code postal : <?= ucfirst($user['code_postal']); ?></p>
        <p>Status : <?= ($user['status'] == 0) ? 'Client' : 'Administrateur'; ?></p>

        <a href="deconnexion.php">Deconnexion</a>

    </div>

</section>

<?php require_once('inc/footer.inc.php');  ?>