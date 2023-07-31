<?php

require_once('config/init.php');

$pageTitle = 'Espace Client';
$pageMetaDesc = 'Bienvenue sur votre espace client.';
$bodyId = MEMBER_PROFILE;


if (userConnected() && $_SESSION['user']['status'] == 0) {

    $id_membre = $_SESSION['user']['id_membre'];

    $requestUser = $bdd->prepare('SELECT * FROM user WHERE id_membre = :id_membre ');
    $requestUser->bindParam(":id_membre", $id_membre, PDO::PARAM_INT);

    try {
        $requestUser->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    $user = $requestUser->fetch(PDO::FETCH_ASSOC);
    extract($user);
} else {
    header('Location: errors/error403.php');
    exit();
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-1-profil">

    <h2>Bienvenue sur votre profil <?= ucfirst($prenom); ?></h2>

    <div class="profil-information">

        <h3>Information de votre profil</h3>
        <hr>

        <p>Prenom : <?= ucfirst($prenom); ?></p>
        <p>Nom : <?= ucfirst($nom); ?></p>
        <p>Email : <?= ucfirst($email); ?></p>
        <p>Civilite : <?= ucfirst($civilite); ?></p>
        <p>Adresse : <?= ucfirst($adresse); ?></p>
        <p>Ville : <?= ucfirst($ville); ?></p>
        <p>Code postal : <?= $code_postal; ?></p>
        <p>Status : <?= ($status == 0) ? 'Client' : 'Administrateur'; ?></p>

        <a href="deconnexion.php">Deconnexion</a>

    </div>

</section>

<?php require_once('inc/footer.inc.php');  ?>