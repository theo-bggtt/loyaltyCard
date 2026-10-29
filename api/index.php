<?php

// naviagateur
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
// fin navigateur

require __DIR__ . '/utils/base.php';
require __DIR__ . '/Compte/account.php';
require __DIR__ . '/Compte/login.php';

// Chemin demandé sans les paramètres, par exemple "/account" pour "/account?user=theo"
$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Sur le serveur l'API est dans un sous-dossier ("/~baggi/api/account"),
// on enlève ce dossier pour garder seulement "/account".
// En local avec php -S, le dossier est vide et rien n'est enlevé.
$dossierApi = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$chemin = substr($chemin, strlen($dossierApi));
$methode = $_SERVER['REQUEST_METHOD'];

if ($chemin === '/account' && $methode === 'POST') {
    creerCompte();
}
elseif ($chemin === '/account' && $methode === 'GET') {
    // Logique pour récupérer les informations du compte
}
elseif ($chemin === '/login' && $methode === 'POST') {
    login();
}

else {
    http_response_code(404);
    echo json_encode(['error' => 'Route introuvable']);
}