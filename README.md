# Skills Share - Plateforme d'Échange de Compétences

**Skills Share** est une application web collaborative permettant à des membres d'échanger leurs compétences et connaissances de manière réciproque. Que ce soit pour enseigner la programmation (Laravel, Python) en échange d'apprentissage de l'anglais, du piano ou de la guitare, la plateforme met en relation des personnes aux besoins et offres complémentaires.

---

## 📐 Schéma d'Architecture & Flux Global du Projet

```text
+---------------------------------------------------------------------------------------------------+
|                                        INTERFACE UTILISATEUR                                      |
|   +---------------------------------------------------+   +-----------------------------------+   |
|   |          Espace Client / Membres                  |   |     Espace Administration         |   |
|   |  - Dashboard / Feed      - Offres & Besoins       |   |  - Statistiques                   |   |
|   |  - Annuaire Membres      - Demandes d'échange     |   |  - Modération Utilisateurs        |   |
|   |  - Messagerie (Chat)     - Catalogue participatif |   |  - Gestion Catégories & Skills    |   |
|   +---------------------------------------------------+   +-----------------------------------+   |
+---------------------------------------------------------------------------------------------------+
                                          |                                   |
                                          v                                   v
                                [ Routes / Auth ]                  [ Routes / Admin ]
                                  (routes/web.php)                   (routes/web.php)
                                          |                                   |
                                          |                                   v
                                          |                        [ Middleware EnsureAdmin ]
                                          |                                   |
                                          +-----------------+-----------------+
                                                            |
                                                            v
+---------------------------------------------------------------------------------------------------+
|                                      CONTRÔLEURS (app/Http/Controllers)                           |
|  +---------------------+  +-------------------------+  +--------------------+  +---------------+  |
|  | MemberController    |  | UserSkillController     |  | CatalogController  |  | ChatController|  |
|  +---------------------+  +-------------------------+  +--------------------+  +---------------+  |
|  +---------------------+  +-------------------------+  +--------------------+                     |
|  | ProfileController   |  | ExchangeRequestController |  | Admin/DashboardCtrl|                     |
|  +---------------------+  +-------------------------+  +--------------------+                     |
+---------------------------------------------------------------------------------------------------+
                                                            |
                                                            v
+---------------------------------------------------------------------------------------------------+
|                                         MODÈLES ELOQUENT                                          |
|                                       (app/Models)                                                |
|                                                                                                   |
|     +--------+ 1     N +-----------+ N     1 +-------+                                            |
|     |  User  |---------| UserSkill |-----------| Skill |                                            |
|     +--------+         +-----------+           +-------+                                            |
|       |    |                                       | 1                                            |
|     1 |    | 1                                     |                                              |
|       |    +-----------------------------+         | N                                            |
|       v N (sender / receiver)            v N       v 1                                            |
|  +-----------------+                +------------------+                                          |
|  | ExchangeRequest |                |     Category     |                                          |
|  +-----------------+                +------------------+                                          |
|       | 1                                                                                         |
|       v (Post-acceptation)                                                                        |
|  +-----------------+ N           1 +---------+                                                    |
|  |  Conversation   |--------------| Message |                                                    |
|  +-----------------+               +---------+                                                    |
+---------------------------------------------------------------------------------------------------+
                                                            |
                                                            v
+---------------------------------------------------------------------------------------------------+
|                                       BASE DE DONNÉES POSTGRESQL                                  |
|                                         (skills_share_db)                                         |
+---------------------------------------------------------------------------------------------------+
```

---

## 📌 Fonctionnalités Principales

### 👤 Côté Membre / Client
1. **Authentification & Gestion du Profil** :
   - Inscription et connexion sécurisées via Laravel Breeze.
   - Complétion du profil : ville, biographie et informations de contact.
2. **Gestion des Offres et Besoins ("Mes Compétences")** :
   - Publication d'offres de compétences avec niveau d'expertise (`débutant`, `intermédiaire`, `expert`) et description détaillée.
   - Publication de besoins de compétences à apprendre.
   - Ajout dynamique au catalogue global de nouvelles catégories ou compétences si elles ne sont pas encore répertoriées.
3. **Fil d'Actualité & Recherche Multicritère** :
   - Fil d'actualité personnalisé affichant les offres et besoins publiés par les autres membres (hors l'utilisateur connecté).
   - Recherche par mot-clé, nom d'utilisateur, ville, nom de compétence ou catégorie.
4. **Annuaire des Membres** :
   - Consultation des profils des membres de la communauté.
   - Filtrage des membres par catégorie, compétence ou type d'annonce (`offre`/`besoin`).
5. **Demandes d'Échange de Compétences** :
   - Envoi de demandes d'échange directes associées à une compétence et accompagnées d'un message explicatif.
   - Suivi des demandes reçues et envoyées.
   - Acceptation ou refus des demandes reçues.
6. **Messagerie Intégrée (Chat en Temps Réel)** :
   - Création automatique d'une conversation privée dès qu'une demande d'échange est acceptée par son destinataire.
   - Démarrage direct de conversations entre membres.
   - Envoi de messages en temps réel (via requêtes AJAX / Fetch API / Polling) avec compteur de messages non lus.

---

### 🛡️ Côté Administration (`/admin`)
1. **Dashboard Admin & Statistiques en Temps Réel** :
   - Vue d'ensemble sur le nombre total d'utilisateurs inscrits, compétences répertoriées, catégories, demandes d'échange et conversations actives.
   - Aperçu rapide des derniers utilisateurs inscrits et des dernières demandes d'échange soumises.
2. **Gestion et Modération des Utilisateurs** :
   - Recherche d'utilisateurs par nom, email ou ville avec pagination.
   - Suppression définitive d'un compte utilisateur en cas d'abus ou de non-respect de la charte.
3. **Gestion du Catalogue (Catégories & Compétences)** :
   - Création et suppression de catégories globales (ex: Informatique, Langues, Musique, Cuisine, Bricolage, Sport).
   - Création et suppression de compétences associées aux catégories avec génération automatique de slugs uniques.
4. **Modération des Offres et Besoins** :
   - Consultation de toutes les compétences publiées par les membres.
   - Filtrage par type (`offre`/`besoin`) ou catégorie, et suppression des annonces inappropriées.
5. **Supervision des Demandes d'Échange** :
   - Historique complet des demandes d'échange de la plateforme.
   - Filtrage par statut (`en_attente`, `acceptee`, `refusee`) et suppression de demandes illégitimes.

---

## 💻 Stack Technique & Architecture

- **Backend** : PHP 8.2+ / Framework **Laravel 12.x**
- **Base de Données** : **PostgreSQL** (`pgsql`, base `skills_share_db`, port `5432`)
- **Authentification Client** : **Laravel Breeze** (session Blade & TailwindCSS)
- **Authentification Admin** : Session d'administration dédiée (`EnsureAdmin` middleware avec identifiants configurés)
- **Frontend & UI** : Blade Templates, **TailwindCSS 4.0**, **Alpine.js**, **Vite.js**, Axios, Concurrently
- **ORM & Migrations** : Eloquent ORM (Relations 1-N, N-N, HasManyThrough)
- **Gestionnaire de Paquets** : Composer (PHP) & NPM (JavaScript)

---

## 📊 Schéma des Entités & Relations (SGBD PostgreSQL)

```text
+-----------------------+           +------------------------+           +-----------------------+
|        users          |           |      user_skills       |           |        skills         |
+-----------------------+           +------------------------+           +-----------------------+
| id (PK)               |<---------1| id (PK)                |           | id (PK)               |
| name                  |           | user_id (FK)           |--------N->| category_id (FK)      |---+
| email                 |           | skill_id (FK)          |           | name                  |   |
| password              |           | type (offre/besoin)    |           | slug                  |   |
| city                  |           | niveau                 |           +-----------------------+   |
| bio                   |           | description            |                                       |
+-----------------------+           +------------------------+                                       |
  ^             ^                                                                                    |
  | (sender)    | (receiver)                                                                         |
  |             |                                                                                    |
  |     +-----------------------+                                        +-----------------------+   |
  +----N|   exchange_requests   |                                        |      categories       |   |
  |     +-----------------------+                                        +-----------------------+   |
  +----N| id (PK)               |                                        | id (PK)               |<--+
        | sender_id (FK)        |                                        | name                  |
        | receiver_id (FK)      |                                        | slug                  |
        | skill_id (FK)         |                                        +-----------------------+
        | message               |
        | status                |
        +-----------------------+
                    ^
                    | 1 (optionnel)
                    |
        +-----------------------+                                        +-----------------------+
        |     conversations     |                                        |       messages        |
        +-----------------------+                                        +-----------------------+
        | id (PK)               |<--------------------------------------1| id (PK)               |
        | user_one_id (FK)      |                                        | conversation_id (FK)  |
        | user_two_id (FK)      |                                        | user_id (FK)          |
        | exchange_request_id   |                                        | body                  |
        +-----------------------+                                        | read_at               |
                                                                         +-----------------------+
```

---

## 📂 Structure Générale des Fichiers et Rôles

```text
skills_share/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AuthController.php        # Connexion/Déconnexion à l'espace administration (/admin/login)
│   │   │   │   └── DashboardController.php   # Dashboard admin, gestion des utilisateurs, catégories, compétences et demandes
│   │   │   ├── Auth/                         # Contrôleurs Breeze (Login, Register, PasswordReset, EmailVerification)
│   │   │   ├── CatalogController.php         # Ajout direct de catégories/compétences au catalogue par les membres
│   │   │   ├── ChatController.php            # Messagerie instantanée (index, show, store, messages AJAX)
│   │   │   ├── ExchangeRequestController.php # Cycle de vie des demandes d'échange (store, update statut, delete)
│   │   │   ├── MemberController.php          # Consultation de l'annuaire des membres et profil public (/members)
│   │   │   ├── ProfileController.php         # Édition et suppression du profil membre (/profile)
│   │   │   └── UserSkillController.php       # Publication et gestion des offres/besoins personnels (/my-skills)
│   │   ├── Middleware/
│   │   │   └── EnsureAdmin.php               # Middleware de sécurité vérifiant la session admin (`is_admin`)
│   │   └── Requests/
│   │       ├── StoreExchangeRequestRequest.php # Validation des demandes d'échange
│   │       └── StoreUserSkillRequest.php       # Validation des publications d'offres ou de besoins
│   └── Models/
│       ├── User.php             # Utilisateur (attributs city, bio, relations offres/besoins, demandes, conversations)
│       ├── Category.php         # Catégorie de compétence (Informatique, Langues, Musique...)
│       ├── Skill.php            # Compétence rattachée à une catégorie (Laravel, Python, Guitare...)
│       ├── UserSkill.php        # Table pivot enrichie (User <-> Skill, type: offre/besoin, niveau, description)
│       ├── ExchangeRequest.php  # Demande d'échange entre sender_id et receiver_id (status: en_attente, acceptee, refusee)
│       ├── Conversation.php     # Fil de discussion entre 2 utilisateurs lié ou non à une demande
│       └── Message.php          # Message de chat rédigé par un utilisateur avec indicateur `read_at`
├── config/                      # Configuration Laravel (database.php configuré sur pgsql, auth.php, app.php...)
├── database/
│   ├── migrations/              # Schémas PostgreSQL des tables (users, categories, skills, user_skills, exchange_requests, conversations, messages)
│   └── seeders/
│       ├── CategorySeeder.php   # Insertion des catégories par défaut
│       ├── SkillSeeder.php      # Insertion des compétences par défaut
│       ├── DemoUsersSeeder.php  # Utilisateurs de démonstration (Alice, Bruno, Clara) avec leurs offres/besoins
│       └── DatabaseSeeder.php   # Seeder principal
├── public/                      # Front controller index.php et ressources statiques
├── resources/
│   ├── css/ & js/               # Assets Vite (app.css, app.js avec Alpine.js et Axios)
│   └── views/
│       ├── admin/               # Vues d'administration (login, dashboard, users, categories, requests, user-skills)
│       ├── auth/                # Vues d'authentification Breeze
│       ├── chat/                # Vues de la messagerie instantanée (index, show)
│       ├── exchange-requests/   # Vues de gestion des demandes d'échange
│       ├── members/             # Vues de l'annuaire et des fiches profils
│       ├── user-skills/         # Vue de gestion des offres/besoins du membre
│       ├── dashboard.blade.php  # Fil d'actualité et tableau de bord principal client
│       └── welcome.blade.php    # Page d'accueil publique
├── routes/
│   ├── web.php                  # Routes web (Membres, Chat, Catalog, Admin)
│   └── auth.php                 # Routes d'authentification Breeze
├── .env                         # Fichier de configuration d'environnement (Connexion DB pgsql)
└── vite.config.js               # Bundler Vite.js pour TailwindCSS et JavaScript
```

---

## 🎬 Scénario d'Utilisation Réel

### 🔹 Scénario Côté Client (Utilisateur / Membre)
1. **Inscription & Profil** : Bruno crée un compte sur la plateforme, remplit sa ville (*Toamasina*) et sa biographie (*"Passionné de musique et d'informatique"*).
2. **Gestion des compétences** :
   - Bruno accède à **"Mes compétences"**.
   - Il publie une **Offre** : *Guitare*, niveau *Expert*, description : *"Je donne des cours de guitare acoustique pour débutants et intermédiaires"*.
   - Il publie un **Besoin** : *Laravel*, afin de créer une application web.
3. **Exploration du Fil d'Actualité** :
   - Bruno consulte son **Tableau de Bord**. Il parcourt le fil d'actualité en temps réel et repère l'annonce d'Alice Rakoto à Antananarivo : *"Aide sur Laravel (CRUD, Eloquent, Breeze)"*.
4. **Demande d'Échange** :
   - Bruno envoie une demande d'échange à Alice avec la compétence *Laravel* et le message : *"Salut Alice, je voudrais apprendre Laravel. On peut échanger contre des cours de guitare ou d'anglais ?"*.
5. **Réception & Validation** :
   - Alice reçoit la notification sur sa page **"Demandes d'échange"**. Elle vérifie les offres de Bruno et clique sur **"Accepter"**.
6. **Chat en Temps Réel** :
   - L'acceptation génère automatiquement une conversation. Alice et Bruno sont redirigés vers le **Chat** (`/chat/{conversation}`) et échangent leurs messages pour fixer les rendez-vous.

---

### 🔹 Scénario Côté Administration (Administrateur)
1. **Connexion Admin** : L'administrateur accède à `/admin/login` et s'authentifie.
2. **Supervision Générale** : Il consulte le dashboard admin (`/admin`) pour suivre le nombre de membres inscrits, de compétences, de demandes d'échange et de messages.
3. **Gestion du Catalogue** : Il crée la catégorie *"Design"* et ajoute la compétence *"Figma"* dans le catalogue officiel.
4. **Modération des Offres et Utilisateurs** :
   - Il consulte l'onglet **"Offres & Besoins"** pour vérifier les publications récentes.
   - En cas d'utilisateur abusif, il se rend dans **"Utilisateurs"** et peut supprimer le compte concerné.

---

## 🔑 Identifiants de Tous les Utilisateurs

### 🛡️ Espace Administration
- **URL d'accès** : `/admin/login`
- **Nom d'utilisateur** : `admin`
- **Mot de passe** : `admin`

---

### 👤 Comptes de Démonstration (Membres)
*(Tous les comptes membres de démonstration partagent le mot de passe : `password`)*

1. **Utilisateur de Test Général** :
   - **Email** : `test@example.com`
   - **Mot de passe** : `password`

2. **Alice Rakoto** *(Ville: Antananarivo)* :
   - **Email** : `alice@demo.test`
   - **Mot de passe** : `password`
   - **Offres** : Laravel (Expert), Python (Intermédiaire)
   - **Besoins** : Anglais

3. **Bruno Raso** *(Ville: Toamasina)* :
   - **Email** : `bruno@demo.test`
   - **Mot de passe** : `password`
   - **Offres** : Guitare (Expert), Anglais (Intermédiaire)
   - **Besoins** : Laravel

4. **Clara Andria** *(Ville: Fianarantsoa)* :
   - **Email** : `clara@demo.test`
   - **Mot de passe** : `password`
   - **Offres** : Anglais (Expert)
   - **Besoins** : React, Piano
