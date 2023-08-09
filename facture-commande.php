<?php

use Dompdf\Dompdf;
use Dompdf\Options;

require_once('config/init.php');
require_once('config/tools/dompdf/autoload.inc.php');

if (!userConnected()) {
    header('Location: errors/error403.php');
    exit();
}


/* Affichage du détail de la commande  */

if (isset($_GET['commande']) && !empty($_GET['commande'])) {

    /* Affichage des informations de la commande */

    $request = $bdd->prepare("SELECT DATE_FORMAT(c.created_at, '%d/%m/%Y') AS 'date_commande', 
                                     DATE_FORMAT(c.update_at, '%d/%m/%Y') AS 'date_update',
                                     c.reference AS 'commande_reference' , c.facture AS 'commande_facture', 
                                     c.livraison, c.adresse_livraison, c.total_ht, c.total_ttc, c.etat, c.id_membre, dc.id_commande
                                     FROM detail_commande dc  
                                     INNER JOIN commande c ON  dc.id_commande = c.id_commande 
                                     AND dc.id_commande = :id_commande");

    $request->bindParam(":id_commande", $_GET['commande'], PDO::PARAM_INT);

    try {
        $request->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    if ($request->rowCount() ==  0) {
        header('Location: errors/error404.php');
        exit();
    }

    $information = $request->fetch(PDO::FETCH_ASSOC);
    extract($information);

    if ($_SESSION['user']['id_membre'] !== $id_membre && $_SESSION['user']['status'] !== "1") {
        header('Location: compte.php');
        exit();
    }

    /* Affichage du détail des produits de la commande */

    $query = $bdd->prepare("SELECT *, prix AS 'detail_prix', quantite AS 'detail_quantite', total AS 'detail_total' FROM detail_commande WHERE id_commande = :id_commande");

    $query->bindParam(":id_commande", $_GET['commande'], PDO::PARAM_INT);

    try {
        $query->execute();
    } catch (PDOException $exception) {
        header('Location: errors/error500.php');
        exit();
    }

    if ($query->rowCount() ==  0) {
        header('Location: errors/error404.php');
        exit();
    }

    /* Génération du pdf */



    $dompdf = new Dompdf();
    $option = new Options();

    $option->set('defaultFont', 'Roboto');
    $option->set('isHtml5ParserEnabled', true);
    $option->set('isRemoteEnabled', true);
    $dompdf->setOptions($option);

    ob_start();
    require_once('inc/pdf-content.inc.php');
    $html = ob_get_contents();
    ob_end_clean();

    $dompdf->loadHtml($html);
    $dompdf->render();
    $dompdf->stream("Facture-" . $commande_facture . ".pdf", [
        'Attachement' => true
    ]);
} else {
    header('Location: errors/error404.php');
    exit();
}
