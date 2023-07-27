<?php


const HOST = "localhost";
const DB_NAME = "boutique";
const USERNAME = "root";
const PASSWORD = "";

function getConnexion()
{

    try {

        $pdo = new PDO(
            "mysql:host=" . HOST . ";dbname=" . DB_NAME,
            USERNAME,
            PASSWORD,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
            )
        );
    } catch (PDOException $exception) {
        echo "<div class=\"message-error\">Erreur de connexion : " . $exception->getMessage() . "</div>";
    }

    return $pdo;
}
