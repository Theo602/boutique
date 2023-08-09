<?php

require_once('../config/init.php');


if (!userIsAdmin()) {
    header('Location: ../errors/error403.php');
}


$pageTitle = 'Espace Admin - Gestion des produits';
$pageMetaDesc = 'Ajouter - modifier - supprimer les produits de la boutique';
$bodyId = ADMIN_GESTION_PRODUIT;


$valid = "";
$error =  [];


if (!isset($_GET['action']) || empty($_GET['action'])) {
    header('Location: ../errors/error404.php');
    exit();
}

if ($_GET['action'] == "ajouter" || $_GET['action'] == "modifier" || $_GET['action'] == "supprimer") {

    if ($_GET['action'] == "modifier" || $_GET['action'] == "supprimer") {

        if (isset($_GET['id_produit']) && !empty($_GET['id_produit'])) {

            $requestProduit = $bdd->prepare('SELECT * FROM produit WHERE id_produit = :id_produit');
            $requestProduit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);

            try {
                $requestProduit->execute();
            } catch (PDOException $exception) {
                header('Location: ../errors/error500.php');
                exit();
            }

            if ($requestProduit->rowCount() == 0) {
                header('Location: ../errors/error404.php');
                exit();
            } else {
                $produit = $requestProduit->fetch(PDO::FETCH_ASSOC);
                extract($produit);
            }
        } else {
            header('Location: ../errors/error404.php');
            exit();
        }
    }

    if ($_POST) {

        extract($_POST);

        $photoBdd = '';

        if ($_GET['action'] == "modifier") {
            $photoBdd = $_POST['photo_actuelle'];
        }

        if (empty($reference) || empty($titre) || empty($description) || empty($prix) || empty($stock)) {
            $error['champs'] = "Veuillez remplir les champs";
        }

        if ($_GET['action'] == "ajouter") {

            // Vérification de l'existence de la référence

            $referenceFind = $bdd->prepare("SELECT * FROM produit WHERE reference = :reference");
            $referenceFind->bindParam(':reference', $reference, PDO::PARAM_STR);

            try {
                $referenceFind->execute();
            } catch (PDOException $exception) {
                header('Location: errors/error500.php');
                exit();
            }

            if ($referenceFind->rowCount() == 1) {
                $error['reference'] = "La référence $reference existe déjà";
            }
        }

        if ((iconv_strlen($reference) < 2 || iconv_strlen($reference) > 60)) {
            $error['reference'] = 'Le champs référence est incorrect';
        }

        if (!preg_match('#^[0-9.]+$#', $prix)) {
            $error['prix'] = 'Le champs prix est incorrect';
        }

        if (!preg_match('#^[0-9.]+$#', $stock)) {
            $error['stock'] = 'Le champs stock est incorrect';
        }

        if (!empty($_FILES['photo']) && $_FILES['photo']['error'] == 0) {

            $autorise = [
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpg',
                'png' => 'image/png'
            ];

            $filename = $_FILES['photo']['name'];
            $filetype = $_FILES['photo']['type'];
            $filesize = $_FILES['photo']['size'];

            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            if (!array_key_exists($extension, $autorise) || !in_array($filetype, $autorise)) {
                $error['photo'] = 'Le format de la photo est incorrect, merci de choisir une image JPEG ou PNG';
            }

            if ($_FILES['photo']['size'] >= 2048 * 2048) {
                $error['size'] = "Vérifier la taille de l'image";
            }

            $nomPhoto = $_POST['reference'] . '_' . $filename;
            $photoBdd = IMG_UPLOAD_URL . "$nomPhoto";
            $photoDossier = IMG_UPLOAD_DIR . "$nomPhoto";

            if ($_GET['action'] == "modifier") {

                $photoActuelle = str_replace(URL, RACINE_SITE, $photo);

                if (!empty($photo) && file_exists($photoActuelle)) {
                    unlink($photoActuelle);
                    copy($_FILES['photo']['tmp_name'], $photoDossier);
                }
            } else {
                copy($_FILES['photo']['tmp_name'], $photoDossier);
            }
        }

        $reference = htmlspecialchars($reference);
        $titre = htmlspecialchars($titre);
        $description = htmlspecialchars($description);
        $prix = htmlspecialchars($prix);
        $stock = htmlspecialchars($stock);
        $couleur = htmlspecialchars($couleur);
        $categorie = htmlspecialchars($categorie);
        $taille = htmlspecialchars($taille);
        $public = htmlspecialchars($public);

        if (empty($error)) {

            $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
            $date = $date->format('Y-m-d H:i:s');

            if ($_GET['action'] == "modifier") {

                $query = $bdd->prepare('UPDATE produit SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo =:photo, prix =:prix, stock =:stock, update_at = :update_at WHERE id_produit = :id_produit');

                $query->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_INT);
                $query->bindParam(":update_at", $date, PDO::PARAM_STR);

                $valid = "La modification du produit " . ucfirst($titre) . ", référence " . $reference .  " a bien été effectué";
            } else {

                $query = $bdd->prepare(" INSERT INTO produit(reference, categorie, titre, description, couleur, taille, public, photo, prix, stock, created_at, update_at )
                VALUES(:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock, :created_at, :update_at)");

                $query->bindParam(":created_at", $date, PDO::PARAM_STR);
                $query->bindParam(":update_at", $date, PDO::PARAM_STR);

                $valid = "Le produit " . ucfirst($titre) . ", référence " . $reference .  " a bien été ajouté";
            }

            $query->bindParam(":reference", $reference, PDO::PARAM_STR);
            $query->bindParam(":categorie", $categorie, PDO::PARAM_STR);
            $query->bindParam(":titre", $titre, PDO::PARAM_STR);
            $query->bindParam(":description", $description, PDO::PARAM_STR);
            $query->bindParam(":couleur", $couleur, PDO::PARAM_STR);
            $query->bindParam(":taille", $taille, PDO::PARAM_STR);
            $query->bindParam(":public", $public, PDO::PARAM_STR);
            $query->bindParam(":photo", $photoBdd, PDO::PARAM_STR);
            $query->bindParam(":prix", $prix, PDO::PARAM_INT);
            $query->bindParam(":stock", $stock, PDO::PARAM_INT);

            try {
                $query->execute();
                $_SESSION['content']['valid'] = $valid;
                header('Location: boutique.php?send=success');
                exit();
            } catch (PDOException $exception) {

                if ($_GET['action'] == "modifier") {
                    header("Location: gestion_produit.php?action=modifier&id_produit=$_GET[id_produit]&send=error");
                    exit();
                } else {
                    header("Location: gestion_produit.php?action=ajouter&send=error");
                    exit();
                }
            }
        }
    }

    if ($_GET['action'] == "supprimer") {

        $photoActuelle = str_replace(URL, RACINE_SITE, $photo);

        if (!empty($photo) && file_exists($photoActuelle)) {

            unlink($photoActuelle);

            $query = $bdd->prepare('DELETE FROM produit WHERE id_produit = :id_produit');
            $query->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_INT);

            try {
                $query->execute();

                $validSupp = "Le produit " . ucfirst($titre) . ", référence " . $reference .  " a bien été supprimé";
                $_SESSION['content']['valid'] = $validSupp;

                header('Location: boutique.php?send=success');
                exit();
            } catch (PDOException $exception) {

                $errorSupp = "Erreur lors de la suppression";
                $_SESSION['content']['error'] = $errorSupp;

                header('Location: boutique.php?send=error');
                exit();
            }
        }
    }
} else {
    header('Location: ../errors/error404.php');
    exit();
}

require_once('inc/header.inc.php');

?>


<!-- Affichage de la page -->

<section class="section-left">

    <?php require_once('inc/menu.inc.php');  ?>

</section>

<section class="section-right">

    <section class="section-1-produit">

        <div class="gestion-form">

            <h3><?= (ucfirst($_GET['action'])) ??  'Erreur';  ?> un produit</h3>
            <hr>

            <?php echo ((isset($_GET['send']) && ($_GET['send'] == "error")) ? "<div class='message-error'>Erreur lors de l'envoi en base de donnée</div>" : "");
            ?>

            <?php if (isset($error['champs'])) : ?>
                <div class="message-error"><?= $error['champs'] ?></div>
            <?php endif ?>


            <form action="" method="POST" enctype="multipart/form-data">

                <div class="reference">

                    <label for="reference">Référence</label>
                    <input class="inputForm <?= isset($error['reference']) ? 'border-error' : '' ?>" type="text" name="reference" id="reference" value="<?= ($reference) ??  '';  ?>">

                    <?php if (isset($error['reference'])) : ?>
                        <div class="message-error-input"><?= $error['reference'] ?></div>
                    <?php endif ?>

                </div>

                <div class="titre">
                    <label for="titre">Titre</label>
                    <input class="inputForm" type="text" name="titre" id="titre" value="<?= ($titre) ??  '';  ?>">
                </div>

                <div class="description">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" cols="30" rows="10"><?= ($description) ??  '';  ?></textarea>
                </div>

                <div class="categorie">
                    <label for="categorie">Categorie</label>
                    <select name="categorie" id="categorie">
                        <option value="t-shirt" <?= (isset($categorie) && $categorie == "t-shirt") ? 'selected' : '';  ?>>T-shirt</option>
                        <option value="pantalon" <?= (isset($categorie) && $categorie == "pantalon") ? 'selected' : '';  ?>>Pantalon</option>
                        <option value="robe" <?= (isset($categorie) && $categorie == "robe") ? 'selected' : '';  ?>>Robe</option>
                        <option value="chaussure" <?= (isset($categorie) && $categorie == "chaussure") ? 'selected' : '';  ?>>Chaussure</option>
                    </select>
                </div>

                <div class="couleur">
                    <label for="couleur">Couleur</label>
                    <select name="couleur" id="couleur">
                        <option value="Bleu" <?= (isset($couleur) && $couleur == "Bleu") ? 'selected' : '';  ?>>Bleu</option>
                        <option value="Rouge" <?= (isset($couleur) && $couleur == "Rouge") ? 'selected' : '';  ?>>Rouge</option>
                        <option value="Noir" <?= (isset($couleur) && $couleur == "Noir") ? 'selected' : '';  ?>>Noir</option>
                        <option value="Jaune" <?= (isset($couleur) && $couleur == "Jaune") ? 'selected' : '';  ?>>Jaune</option>
                        <option value="Vert" <?= (isset($couleur) && $couleur == "Vert") ? 'selected' : '';  ?>>Vert</option>
                        <option value="Blanc" <?= (isset($couleur) && $couleur == "Blanc") ? 'selected' : '';  ?>>Blanc</option>
                        <option value="Violet" <?= (isset($couleur) && $couleur == "Violet") ? 'selected' : '';  ?>>Violet</option>
                        <option value="Orange" <?= (isset($couleur) && $couleur == "Orange") ? 'selected' : '';  ?>>Orange</option>
                    </select>
                </div>

                <div class="taille">
                    <label for="taille">Taille</label>
                    <select name="taille" id="taille">
                        <option value="XS" <?= (isset($taille) && $taille == "XS") ? 'selected' : '';  ?>>XS</option>
                        <option value="S" <?= (isset($taille) && $taille == "S") ? 'selected' : '';  ?>>S</option>
                        <option value="M" <?= (isset($taille) && $taille == "M") ? 'selected' : '';  ?>>M</option>
                        <option value="L" <?= (isset($taille) && $taille == "L") ? 'selected' : '';  ?>>L</option>
                        <option value="XL" <?= (isset($taille) && $taille == "XL") ? 'selected' : '';  ?>>XL</option>
                        <option value="XXL" <?= (isset($taille) && $taille == "XXL") ? 'selected' : '';  ?>>XXL</option>
                    </select>
                </div>

                <div class="public">
                    <select name="public" id="public">
                        <option value="homme" <?= (isset($public) && $public == "homme") ? 'selected' : '';  ?>>Homme</option>
                        <option value="femme" <?= (isset($public) && $public == "femme") ? 'selected' : '';  ?>>Femme</option>
                        <option value="mixte" <?= (isset($public) && $public == "mixte") ? 'selected' : ''; ?>>Mixte</option>
                    </select>
                </div>

                <div class="photo">
                    <label for="photo">Photo</label>
                    <input type="file" class="inputForm <?= isset($error['photo']) ? 'border-error' : '' ?>" name="photo" id="photo">

                    <?php if (isset($error['photo'])) : ?>
                        <div class="message-error-input"><?= $error['photo'] ?></div>
                    <?php endif ?>

                    <?php if (isset($error['size'])) : ?>
                        <div class="message-error-input"><?= $error['size'] ?></div>
                    <?php endif ?>

                    <?php if (isset($photo)) : ?>
                        <label for="photo_actuelle">Vous pouvez choisir une nouvelle photo pour votre produit</label>
                        <figure>
                            <img src="<?= $photo; ?>" alt="Photo actuelle">
                        </figure>

                        <input class="inputForm" type="hidden" name="photo_actuelle" id="photo_actuelle" value="<?= $photo; ?>">
                    <?php endif ?>

                </div>

                <div class="prix">
                    <label for="prix">Prix</label>
                    <input class="inputForm <?= isset($error['stock']) ? 'border-error' : '' ?>" type="text" name="prix" id="prix" value="<?= ($prix) ??  '';  ?>">

                    <?php if (isset($error['prix'])) : ?>
                        <div class="message-error-input"><?= $error['prix'] ?></div>
                    <?php endif ?>

                </div>

                <div class="stock">
                    <label for="stock">Stock</label>
                    <input class="inputForm <?= isset($error['stock']) ? 'border-error' : '' ?>" type="text" name="stock" id="stock" value="<?= ($stock) ??  '';  ?>">

                    <?php if (isset($error['stock'])) : ?>
                        <div class="message-error-input"><?= $error['stock'] ?></div>
                    <?php endif ?>

                </div>

                <div class="submit">
                    <input class="inputForm submit" type="submit" name="valid" value="<?= (ucfirst($_GET['action'])) ??  'Erreur'; ?>">
                </div>

            </form>

        </div>

    </section>

</section>

<?php require_once('../admin/inc/footer.inc.php');  ?>