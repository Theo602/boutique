<?php

/* Fonction membre connecté */
function userConnected()
{
    if (!isset($_SESSION['user'])) {
        return false;
    } else {
        return true;
    }
}

/* Fonction admin connecté */

function userIsAdmin()
{
    if (userConnected() && $_SESSION['user']['status'] == 1) {
        return true;
    } else {
        return false;
    }
}

/* Fonction création du panier dans la session */

function createCart()
{
    if (!isset($_SESSION['panier'])) {

        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['photo'] = array();
        $_SESSION['panier']['reference'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

/* Fonction qui ajoute un produit au panier dans la session */

function addProduit($id_produit, $photo, $reference, $titre, $quantite, $prix, $stock)
{
    createCart();

    $index_IdProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if ($index_IdProduit !== false) {
        if ($_SESSION['panier']['quantite'][$index_IdProduit] >= $stock) {
            $error = "Désolé, il n'y a plus de stock pour le produit <b>" . ucfirst($titre) . "</b>";
            $_SESSION['content']['error'] = $error;
        } else {
            $_SESSION['panier']['quantite'][$index_IdProduit] += $quantite;
            $valid = "Le produit <b>" . ucfirst($titre) . "</b> a été ajouté au panier";
            $_SESSION['content']['valid'] = $valid;
        }
    } else {
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['photo'][] = $photo;
        $_SESSION['panier']['reference'][] = $reference;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;

        $valid = "Le produit <b>" . ucfirst($titre) . "</b> a été ajouté au panier";
        $_SESSION['content']['valid'] = $valid;
    }
}

/* Fonction qui supprime un produit au panier dans la session */

function deleteProduit($id_produit)
{
    $indexProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if ($indexProduit !== false) {

        array_splice($_SESSION['panier']['id_produit'], $indexProduit, 1);
        array_splice($_SESSION['panier']['photo'], $indexProduit, 1);
        array_splice($_SESSION['panier']['reference'], $indexProduit, 1);
        array_splice($_SESSION['panier']['titre'], $indexProduit, 1);
        array_splice($_SESSION['panier']['quantite'], $indexProduit, 1);
        array_splice($_SESSION['panier']['prix'], $indexProduit, 1);
    }
}

/* Fonction qui ajoute une quantité d'un produit au panier dans la session */

function addQuantiteProduit($id_produit, $stock, $nomProduit)
{
    $indexAddProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if ($indexAddProduit !== false) {

        if ($_SESSION['panier']['quantite'][$indexAddProduit] >= $stock) {
            $error = "Désolé, il n'y a plus de stock pour le produit <b>" . ucfirst($nomProduit) . "</b>";
            $_SESSION['content']['error'] = $error;
        } else {
            $_SESSION['panier']['quantite'][$indexAddProduit] += 1;
            $valid = "Le produit <b>" . ucfirst($nomProduit) . "</b> a été ajouté au panier";
            $_SESSION['content']['valid'] = $valid;
        }
    }
}

/* Fonction qui supprime une quantité d'un produit au panier dans la session */

function deleteQuantiteProduit($id_produit)
{
    $indexdeleteProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if ($indexdeleteProduit !== false) {

        if ($_SESSION['panier']['quantite'][$indexdeleteProduit] > 1) {
            $_SESSION['panier']['quantite'][$indexdeleteProduit] -= 1;
        } else {
            array_splice($_SESSION['panier']['id_produit'], $indexdeleteProduit, 1);
            array_splice($_SESSION['panier']['photo'], $indexdeleteProduit, 1);
            array_splice($_SESSION['panier']['reference'], $indexdeleteProduit, 1);
            array_splice($_SESSION['panier']['titre'], $indexdeleteProduit, 1);
            array_splice($_SESSION['panier']['quantite'], $indexdeleteProduit, 1);
            array_splice($_SESSION['panier']['prix'], $indexdeleteProduit, 1);
        }
    }
}

/* Fonction qui supprime le panier de la session */

function deletePanier()
{
    unset($_SESSION['panier']);
}

/* Fonction qui calcul le montant total du produit */

function produitTotal($id_produit)
{
    $indexProduitTotal = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if ($indexProduitTotal !== false) {
        $total = 0;
        $total = $_SESSION['panier']['quantite'][$indexProduitTotal] * $_SESSION['panier']['prix'][$indexProduitTotal];
    }
    return round($total, 2);
}

/* Fonction qui calcul le montant total hors taxe du produit */

function prixTotalHT()
{
    $total = 0;
    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }
    return round($total, 2);
}

/* Fonction qui calcul la Tva du produit */

function tauxTva($prixTotalHT)
{
    $tva = 0;
    $tva = $prixTotalHT * 20 / 100;
    return round($tva, 2);
}

/* Fonction qui calcul le montant total TCC du produit */

function prixTotalTCC($prixTotalHT, $tva)
{
    $total = 0;
    $total = $prixTotalHT + $tva;
    return round($total, 2);
}

/* Fonction qui retourne une chaîne de caractère aléatoire pour la référence de la commande */

function chaineAleatoire($lenght = 10)
{
    $chaine = "";
    $caractere = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $lenghtMax = strlen($caractere);

    for ($i = 0; $i < $lenght; $i++) {
        $chaine .= $caractere[rand(0, $lenghtMax - 1)];
    }
    return $chaine;
}
