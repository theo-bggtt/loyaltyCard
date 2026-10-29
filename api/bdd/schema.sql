-- Schéma de la base de données LoyaltyCard (MariaDB)
-- ENGINE=InnoDB est nécessaire pour que les clés étrangères soient appliquées

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomUtilisateur VARCHAR(50) NOT NULL UNIQUE,
    -- Hash généré par password_hash(), jamais le mot de passe en clair
    -- 255 caractères comme recommandé par la doc PHP
    password VARCHAR(255) NOT NULL,
    -- Rempli au login, remis à NULL au logout
    token VARCHAR(255) UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cartes (
    idCarte INT PRIMARY KEY AUTO_INCREMENT,
    nomCarte VARCHAR(100) NOT NULL,
    -- En texte pour garder les zéros au début du numéro
    numCarte VARCHAR(100) NOT NULL,
    -- Format renvoyé par le scanner, par exemple "QR_CODE" ou "EAN_13"
    typeCode VARCHAR(30) NOT NULL,
    couleurCarte VARCHAR(20) NOT NULL,
    -- Optionnel, en texte pour garder un PIN comme "0123"
    pin VARCHAR(20),
    idUtilisateur INT NOT NULL,
    -- Si le compte est supprimé, ses cartes le sont aussi
    FOREIGN KEY (idUtilisateur) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS partage (
    idPartage INT PRIMARY KEY AUTO_INCREMENT,
    idUtilisateur INT NOT NULL,
    idCarte INT NOT NULL,
    idInvite INT NOT NULL,
    FOREIGN KEY (idUtilisateur) REFERENCES users(id) ON DELETE CASCADE,
    -- Supprimer une carte supprime aussi ses partages (demandé par la doc)
    FOREIGN KEY (idCarte) REFERENCES cartes(idCarte) ON DELETE CASCADE,
    FOREIGN KEY (idInvite) REFERENCES users(id) ON DELETE CASCADE,
    -- Empêche de partager deux fois la même carte avec la même personne
    UNIQUE (idCarte, idInvite)
) ENGINE=InnoDB;
