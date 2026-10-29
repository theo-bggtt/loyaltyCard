---
description: Rappelle comment déployer l'API LoyaltyCard sur le serveur de l'école
---

Rappelle-moi comment déployer l'API sur le serveur de l'école. Réponds en français, de façon courte et dans l'ordre des étapes.

## Contexte (déjà mis en place)

- Serveur : `devmob.ictge.ch`, SSH sur le port **22004**, utilisateur `baggi`.
- Le dossier `~/public_html` est servi sur `https://devmob.ictge.ch/~baggi`. L'API va dans `~/public_html/api`, donc l'URL de base de l'API est `https://devmob.ictge.ch/~baggi/api`.
- Apache 2.4 (derrière nginx) avec PHP. La base MariaDB est sur la même machine : depuis le serveur, on s'y connecte en `localhost:3306`.
- `api/.htaccess` envoie toutes les requêtes vers `index.php`. Son `RewriteBase /~baggi/api/` doit correspondre au dossier de déploiement.
- `api/index.php` enlève le dossier de l'API du chemin, donc `/~baggi/api/account` est traité comme `/account`.
- `api/utils/config.php` contient les identifiants de la base. Il est dans le `.gitignore` et ne doit **jamais** être copié avec scp ni commité. Sur le serveur, il est créé une seule fois à la main à partir de `config.exemple.php`, avec `BASE_HOTE` = `localhost` et `BASE_PORT` = `3306`.
- Ne jamais envoyer `api/bdd/*.sqlite`, c'est l'ancienne base qui ne sert plus.
- En local, DBeaver passe par un tunnel SSH (port 22004 vers `localhost:3306` côté serveur). Le port 3306 n'est pas ouvert depuis l'extérieur.

## Ce que tu dois faire

1. Regarde `git status` et `git log` pour voir quels fichiers de `api/` ont changé depuis le dernier déploiement, et liste aussi les fichiers présents dans `api/` (hors `config.php` et `*.sqlite`).
2. Donne-moi les commandes à lancer moi-même depuis le dossier `api/` :
   - `ssh -p 22004 baggi@devmob.ictge.ch "mkdir -p ..."` pour chaque sous-dossier nécessaire dans `public_html/api`
   - `scp -P 22004 <fichiers> baggi@devmob.ictge.ch:public_html/api/<dossier>/`, regroupées par dossier, en excluant `config.php` et `*.sqlite`
3. Rappelle les vérifications :
   - Ouvrir `https://devmob.ictge.ch/~baggi/api/account` dans un navigateur. Erreur 500 = PHP tourne mais `config.php` manque. Code PHP affiché en texte = PHP n'est pas activé : NE PAS créer `config.php`, demander au prof. Page « Not Found » d'Apache = `.htaccess` ignoré.
   - Si `config.php` n'existe pas encore sur le serveur : `ssh -p 22004 baggi@devmob.ictge.ch`, puis `cd public_html/api/utils && cp config.exemple.php config.php && nano config.php`.
   - Tester dans Postman `POST https://devmob.ictge.ch/~baggi/api/account` avec un body JSON `{"user": "...", "pass": "..."}`. Attendu : 201, puis 409 au deuxième envoi, et la ligne visible dans DBeaver.
4. Si `api/utils/auth.php` lit l'en-tête `Authorization` et que le token arrive vide sur le serveur, signale qu'Apache avec PHP-FPM ne transmet pas cet en-tête et propose la ligne à ajouter au `.htaccess`.

Ne lance pas toi-même ssh ni scp : ils demandent mon mot de passe et ils modifient le serveur. Donne-moi les commandes, c'est moi qui les lance.
