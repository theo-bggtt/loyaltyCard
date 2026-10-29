<?php
require_once __DIR__ . '/config.php';

function connexionBase()
{
    $adresseBase = 'mysql:host=' . BASE_HOTE . ';port=' . BASE_PORT . ';dbname=' . BASE_NOM . ';charset=utf8mb4';
    $base = new PDO($adresseBase, BASE_UTILISATEUR, BASE_MOT_DE_PASSE);
    $base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $base->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $base;
}

