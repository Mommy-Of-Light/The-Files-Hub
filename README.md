# The-Files-Hub

Notre projet a pour objectif de créer un site web où les utilisateurs pourront publier leurs fichiers sans limitation et télécharger ceux des autres.

---

## Dépot du site

Le site est disponible sur [https://github.com/Mommy-Of-Light/The-Files-Hub.git](https://github.com/Mommy-Of-Light/The-Files-Hub.git)

---

## Fonctionnalités

* API REST avec Slim Framework
* Gestion des requêtes/réponses PSR-7
* Injection de dépendances
* Configuration par variables d’environnement
* Réponses JSON
* Support des middlewares

--- 

## Prérequis

* PHP >= 8.1
* Composer
* Web server (Apache / Nginx) or PHP built-in server
* MySQL / PostgreSQL (optionnel)

---

## Installation

Cloner le dêpot:

```bash
git clone https://github.com/Mommy-Of-Light/The-Files-Hub.git
cd The-Files-Hub
```

Installer les dépendances :

```bash
composer install
```

---

## Configuration de l’environnement

### Mise en place de la base de données

```bash
mysql -u <user> -p < sql/database.sql
```

Ou copiez le script sql `sql/database.sql` dans votre server mariadb.

### Copier le fichier d’exemple :

```bash
cp config/database.sample.php config/database.php
```

### Modifier les variables selon votre configuration :

Mettez vos informations de connexion a mariadb.

```bash
<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'file_hub');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_CHARSET', 'utf8mb4');
```

---

## Lancer l’application

Avec le serveur PHP intégré :

```bash
composer start
```

Accéder à l’application :

http://localhost:8000

Arrèter l'application:

```bash
composer stop
```

Les messages: 

- `Script php -S localhost:8080 -d display_errors=1 -d display_startup_errors=1 -t public handling the start event returned with error code 143`
- `Script pkill -f 8080 handling the stop event returned with error code 143`

ne sont pas importants et cela n'affecte pas le lancemant et l'arret du server.

---

## Architecture

```
The-Files-Hub/
│
├── Doc/
│   ├── MCD-MLD/
│   │   ├── MCD.png
│   │   └── MLD.dbml
│   ├── CahierCharges.md
│   ├── Gantt-Planing.gan
│   ├── RapportTests.md
│   └── JournalBord.md
│
├── src/
│   ├── Controllers/
│   │   ├── BaseController.php
│   │   ├── HomeController.php
│   │   ├── LoginController.php
│   │   ├── ModeratorController.php
│   │   └── PostController.php
│   ├── Core/
│   │   └── Database.php
│   ├── Models/
│   │   ├── AbstractModel.php
│   │   ├── Post.php
│   │   └── User.php
│   └── Services/
│       └── UserService.php
│
├── config/
│   └── database.sample.php
│
├── public/
│   ├── assets/
│   │   ├── defauts/
│   │   │   ├── audio.png
│   │   │   ├── exe.png
│   │   │   ├── image.png
│   │   │   ├── text.png
│   │   │   ├── untracked.png
│   │   │   └── video.png
│   │   └── pfp/
│   ├── uploads/
│   │   └── posts/
│   ├── index.php
│   └── .htaccess
│
├── routes/
│   └── web.php
│
├── sql/
│   └── database.sql
│
├── views/
│   ├── errors/
│   │   ├── 401.php
│   │   ├── 404.php
│   │   ├── 418.php
│   │   └── 500.php
│   ├── home/
│   │   ├── home.php
│   │   └── profile.php
│   ├── login/
│   │   ├── login.php
│   │   └── register.php
│   ├── posts/
│   │   ├── all.php
│   │   ├── new.php
│   │   ├── single.php
│   │   └── update.php
│   ├── roles/
│   │   └── moderators.php
│   ├── layout.php
│   └── menu.php
│
├── vendor/
├── composer.json
├── composer.lock
└── README.md
```

---


## Routes de l'application

### Accueil

| Méthode | Route     | Description                    |
| ------- | --------- | ------------------------------ |
| GET     | `/`       | Page d'accueil                 |
| GET     | `/secret` | Page secrète (accès restreint) |

---

## Utilisateur / Profil

| Méthode | Route                 | Description                         |
| ------- | --------------------- | ----------------------------------- |
| GET     | `/profile`            | Afficher le profil de l'utilisateur |
| POST    | `/profile/update-pfp` | Mettre à jour la photo de profil    |
| POST    | `/profile/delete`     | Supprimer le compte utilisateur     |

---

## Authentification

| Méthode | Route       | Description                    |
| ------- | ----------- | ------------------------------ |
| GET     | `/login`    | Afficher la page de connexion  |
| POST    | `/login`    | Authentifier l'utilisateur     |
| GET     | `/register` | Afficher la page d'inscription |
| POST    | `/register` | Créer un nouveau compte        |
| GET     | `/logout`   | Déconnecter l'utilisateur      |

---

## Modération / Administration

| Méthode | Route                      | Description                     |
| ------- | -------------------------- | ------------------------------- |
| GET     | `/mod`                     | Tableau de bord des modérateurs |
| GET     | `/mod/admin-dashboard`     | Dashboard administrateur        |
| GET     | `/mod/opperator-dashboard` | Dashboard opérateur             |

### Gestion des utilisateurs

| Méthode | Route                    | Description                |
| ------- | ------------------------ | -------------------------- |
| POST    | `/mod/user/promote/{id}` | Promouvoir un utilisateur  |
| POST    | `/mod/user/demote/{id}`  | Rétrograder un utilisateur |
| POST    | `/mod/user/delete/{id}`  | Supprimer un utilisateur   |

---

## Posts

### Consultation

| Méthode | Route               | Description             |
| ------- | ------------------- | ----------------------- |
| GET     | `/posts`            | Liste de tous les posts |
| GET     | `/post/single/{id}` | Voir un post spécifique |

---

### Création

| Méthode | Route       | Description                    |
| ------- | ----------- | ------------------------------ |
| GET     | `/post/new` | Formulaire de création de post |
| POST    | `/post/new` | Soumettre un nouveau post      |

---

### Modification

| Méthode | Route                      | Description                    |
| ------- | -------------------------- | ------------------------------ |
| GET     | `/post/single/{id}/update` | Page de modification d’un post |
| POST    | `/post/single/{id}/update` | Mettre à jour un post          |

---

### Suppression

| Méthode | Route                      | Description       |
| ------- | -------------------------- | ----------------- |
| POST    | `/post/single/{id}/delete` | Supprimer un post |

---

### Likes / Réactions

| Méthode | Route                      | Description                                           |
| ------- | -------------------------- | ----------------------------------------------------- |
| POST    | `/post/single/{id}/{type}` | Ajouter ou retirer une réaction (like, dislike, etc.) |

**Paramètres :**

| Paramètre | Description         |
| --------- | ------------------- |
| `id`      | Identifiant du post |
| `type`    | Type de réaction    |



---

## Commandes utiles

Vider le cache Composer :

```bash
composer clear-cache
```

Mettre à jour les dépendances :

```bash
composer update
```

---

## Contribution

1. Forker le dépôt
2. Créer une branche pour votre fonctionnalité :

```bash
git checkout -b feature/ma-fonctionnalite
```

3. Commiter vos modifications
4. Pousser votre branche
5. Ouvrir une Pull Request

---

## License

Ce projet est sous licence MIT.

---

## Auteurs

Bastien: bastien.jcln@eduge.ch

Kevin: kevin.rsmdn@eduge.ch

