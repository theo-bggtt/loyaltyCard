# Loyalty Card

## Fonctionnalités

### Principal

- **Lister les cartes de fidélités** : écran d'accueil affichant toutes les cartes de l'utilisateur lié à son compte.
- **Générer un qr-code fonctionnel pour chaque carte (hors ligne compatible)** : le code est généré localement en JavaScript à partir du numéro stocké, aucune requête réseau n'est nécessaire pour l'afficher en caisse, mais le réseau est nécessaire pour synchroniser
- **Un menu d'ajout de carte de fidélité** : soit par scan avec la caméra (le plugin renvoie la valeur et le format), soit par saisie manuelle du numéro.
- **Persistance des cartes (inter-appareil)** : après connexion sur un nouvel appareil, les cartes sont retéléchargées depuis le serveur, à travers le compte de l'utilisateur.
- **Couleurs, noms et numéro personnalisable des cartes** : chaque carte a un nom, une couleur choisie dans une palette et un numéro modifiable.
- **Supréssion des cartes** : suppression locale immédiate puis serveur et autres appareils connectés et partagés.

### Optionnel

- **Ajouter un code secret pour chaque carte** : code PIN à 4 chiffres associé à une carte, affiché uniquement après saisie du code.
- **Partager la carte à un autre utilisateur** : envoi d'une copie de la carte au compte destinataire (recherche par nom d'utilisateur).
- **Jeux blackjack pour augmenter le nombre de carte maximum** : mini-jeu qui augmente la limite de cartes du compte en cas de victoire.

### Fonctionnalités techniques transverses

- Création de compte / connexion / déconnexion.
- Mode hors-ligne complet : consultation, ajout, modification et suppression possibles sans réseau, synchronisation automatique au retour de la connexion.
- Notification locale quand une carte est reçue en partage ou lors d'une nouvelle connexion sur le compte.

## Planning prévisionnel

Échéances imposées :

| Livrable            | Échéance   |
| ------------------- | ---------- |
| Analyse du projet   | 23.09.2026 |
| Documentation API   | 07.10.2026 |
| API REST            | 28.10.2026 |
| App mobile          | 13.12.2026 |
| Rapport final       | 03.01.2027 |

## Bibliothèque et plugins

- **StarleyDev/barcodescanner-sdk31** - Permet de scanner avec la camera, tout type de qr code, code barre pour ajouter une nouvelle carte
- **SQLite** - Pour gérer les utilisateurs (base de donnée) ainsi que leur carte et le partage de leur cartes

## Architecture du projet

- `/index.html` - pour la page principale avec l'affichage de carte et l'ajout de carte
- `/login.html` - pour le formulaire de login et register de l'utilisateur
- `/js/index.js` - gestion des interactions principales de la page index
- `/js/login.js` - gestion de login et de création de compte
- `/js/card.js` - tout ce qui est en lien avec la gestion, ajout, suppression des cartes de l'utilisateur
- `/js/db.js` - toutes les fonctions qui font des requêtes sql (séparé pour des raisons pratiques mais peut disparaitre et être intégré dans les autres .js)
- `/css/*.css` - style

## Gestion du mode hors-connexion

Le réseau ne cera nécessaire que au moment ou l'utilisateur souhaite se connecter ou créer un compte, dans le cas contraire aucun réseau n'est nécessaire. Quand l'utilisateur se connecte à son compte, l'application synchronisera ses cartes avec celle de son telephone et vise-verca.

## Schéma de la base de donnéee

### Table 'users'
- id
- nomUtilisateur
- password (hashé)

### Table 'cartes'
- idcarte
- nomcarte
- numCarte
- couleurCarte
- idUtilisateur

### Table 'partage'
- idPartage
- idUtilisateur
- idCarte
- idInvité

## API REST

### POST /account

- user
- pass

### GET /account

- user

### POST /cards

- nomCarte
- numCarte
- CouleurCarte
- IdUtilisateur

### GET /cards

- idCarte / idUtilisateur

### POST /partage

- idCarte
- idUtilisateur
- idInvité

### get /partage

- idInvité / idCarte
