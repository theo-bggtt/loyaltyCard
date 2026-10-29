<?php
// POST /account : crée un nouveau compte
function creerCompte()
{
    // Le corps de la requête est du JSON, $_POST ne sait pas le lire
    $donnees = json_decode(file_get_contents('php://input'), true);

    if (empty($donnees['user'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champ manquant : user']);
        return;
    }
    if (empty($donnees['pass'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champ manquant : pass']);
        return;
    }

    $nomUtilisateur = $donnees['user'];
    $base = connexionBase();

    if (utilisateurExiste($base, $nomUtilisateur)) {
        http_response_code(409);
        echo json_encode(['error' => "Nom d'utilisateur déjà pris"]);
        return;
    }

    // Ne jamais stocker le mot de passe en clair
    $motDePasseHashe = password_hash($donnees['pass'], PASSWORD_DEFAULT);

    // Requête préparée : les "?" empêchent les injections SQL
    $requete = $base->prepare('INSERT INTO users (nomUtilisateur, password) VALUES (?, ?)');
    $requete->execute([$nomUtilisateur, $motDePasseHashe]);

    http_response_code(201);
    echo json_encode([
        // lastInsertId() renvoie du texte, (int) pour avoir "id": 1 et pas "id": "1"
        'id' => (int) $base->lastInsertId(),
        'user' => $nomUtilisateur
    ]);
}

// Renvoie true si le nom d'utilisateur est déjà dans la base
function utilisateurExiste($base, $nomUtilisateur)
{
    $requete = $base->prepare('SELECT id FROM users WHERE nomUtilisateur = ?');
    $requete->execute([$nomUtilisateur]);
    return $requete->fetch() !== false;
}


function login()
{
    // Le corps de la requête est du JSON, $_POST ne sait pas le lire
    $donnees = json_decode(file_get_contents('php://input'), true);

    if (empty($donnees['user'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champ manquant : user']);
        return;
    }
    if (empty($donnees['pass'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Champ manquant : pass']);
        return;
    }

    $nomUtilisateur = $donnees['user'];
    $motDePasse = $donnees['pass'];
    $base = connexionBase();

    $requete = $base->prepare('SELECT id, password FROM users WHERE nomUtilisateur = ?');
    $requete->execute([$nomUtilisateur]);
    $utilisateur = $requete->fetch();

    if (!$utilisateur || !password_verify($motDePasse, $utilisateur['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Nom d\'utilisateur ou mot de passe incorrect']);
        return;
    }

    http_response_code(200);
    // random_bytes est imprévisible, contrairement à uniqid() qui se base sur l'heure
    $newToken = bin2hex(random_bytes(32));
    updateToken($utilisateur['id'], $newToken);
    echo json_encode([
        'id' => (int) $utilisateur['id'],
        'user' => $nomUtilisateur,
        'token' => $newToken
    ]);
}

function updateToken($userId, $token)
{
    $base = connexionBase();
    $requete = $base->prepare('UPDATE users SET token = ? WHERE id = ?');
    $requete->execute([$token, $userId]);
}