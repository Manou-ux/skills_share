# Skills Share - Plateforme d'Échange de Compétences

**Skills Share** est une application web collaborative permettant à des individus de partager, enseigner et apprendre des compétences de manière réciproque. Que ce soit pour échanger des cours de programmation (ex: Laravel, Python) contre des cours d'anglais, de guitare ou de cuisine, la plateforme met en relation des membres aux besoins et offres complémentaires.

---

## 📌 Fonctionnalités Principales

### 👤 Côté Membre / Client
1. **Authentification & Gestion de Profil** :
   - Inscription et connexion sécurisées.
   - Personnalisation du profil : Ville, biographie et photo/avatar.
2. **Gestion des Offres et Besoins ("Mes Compétences")** :
   - Publication d'offres de compétences (avec niveau d'expertise : Débutant, Intermédiaire, Expert) et description.
   - Publication de besoins de compétences à apprendre.
   - Ajout dynamique au catalogue de nouvelles catégories ou compétences si elles n'existent pas encore.
3. **Fil d'actualité & Recherche intelligente** :
   - Fil d'actualité personnalisé affichant les offres et besoins des autres membres.
   - Barre de recherche multicritère (par nom de membre, ville, nom de compétence, catégorie ou mot-clé).
4. **Annuaire des Membres** :
   - Consultation des profils des membres de la communauté avec leurs offres et besoins.
   - Filtrage des membres par catégorie, compétence ou type d'annonce.
5. **Demandes d'Échange de Compétences** :
   - Envoi de demandes d'échange ciblées avec message personnalisé.
   - Suivi des demandes envoyées et reçues.
   - Acceptation ou refus des demandes.
6. **Messagerie Intégrée (Chat en Temps Réel)** :
   - Création automatique d'une conversation dès qu'une demande d'échange est acceptée.
   - Possibilité d'initier un chat direct avec n'importe quel membre.
   - Messagerie instantanée réactive avec compteur de messages non lus et rafraîchissement dynamique.

---

### 🛡️ Côté Administration (`/admin`)
1. **Tableau de Bord & Statistiques** :
   - Vue globale du nombre d'utilisateurs, de compétences enregistrées, de catégories, de demandes d'échange et de conversations.
   - Aperçu rapide des derniers utilisateurs inscrits et des dernières demandes d'échange.
2. **Gestion des Utilisateurs** :
   - Recherche et filtrage des utilisateurs par nom, email ou ville.
   - Modération et suppression des comptes non conformes ou abusifs.
3. **Gestion du Catalogue (Catégories & Compétences)** :
   - Création, édition et suppression de catégories globales (ex: Informatique, Langues, Musique).
   - Création et suppression de compétences rattachées aux catégories.
4. **Modération des Compétences Utilisateurs** :
   - Supervision de toutes les offres et besoins publiés sur le fil d'actualité.
   - Filtrage par type (`offre`/`besoin`) ou catégorie, et suppression des publications inappropriées.
5. **Supervision des Demandes d'Échange** :
   - Consultation et filtrage de l'historique complet des demandes d'échange (`en_attente`, `acceptee`, `refusee`).
   - Suppression des demandes illégitimes.

---

## 💻 Stack Technique

- **Framework Backend** : PHP 8.2+ / [Laravel 11.x](https://laravel.com)
- **Authentification Client** : [Laravel Breeze](https://laravel.com/docs/breeze) (Blade, TailwindCSS)
- **Authentification Admin** : Système de session dédié via middleware `EnsureAdmin`
- **Frontend & UI** : Blade Templates, TailwindCSS, Alpine.js, Vite.js
- **Base de données** : MySQL / SQLite (via Eloquent ORM)
- **Tooling & Dépendances** : Composer, NPM, Artisan CLI

---

## 📂 Structure Générale des Fichiers et Rôles

```text
skills_share/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AuthController.php        # Authentification et session de l'espace administration
│   │   │   │   └── DashboardController.php   # Dashboard admin, gestion des utilisateurs, catégories, compétences et demandes
│   │   │   ├── Auth/                         # Contrôleurs d'authentification Breeze (Login, Register, Password, Email)
│   │   │   ├── CatalogController.php         # Ajout direct de catégories/compétences par les membres
│   │   │   ├── ChatController.php            # Gestion des conversations privées, messages et API AJAX
│   │   │   ├── ExchangeRequestController.php # Gestion du cycle de vie des demandes d'échange (envoyer, accepter, refuser)
│   │   │   ├── MemberController.php          # Consultation de l'annuaire des membres et profil public
│   │   │   ├── ProfileController.php         # Édition et suppression du profil utilisateur
│   │   │   └── UserSkillController.php       # Publication et suppression des offres/besoins personnels
│   │   ├── Middleware/
│   │   │   └── EnsureAdmin.php               # Middleware de sécurité protégeant l'accès aux routes /admin
│   │   └── Requests/
│   │       ├── StoreExchangeRequestRequest.php # Validation pour l'envoi d'une demande d'échange
│   │       └── StoreUserSkillRequest.php       # Validation pour la publication d'une offre ou d'un besoin
│   └── Models/
│       ├── User.php             # Modèle Utilisateur (profil, compétences, demandes, conversations)
│       ├── Category.php         # Modèle Catégorie (ex: Informatique, Langues)
│       ├── Skill.php            # Modèle Compétence (ex: Laravel, Guitare)
│       ├── UserSkill.php        # Modèle de liaison Utilisateur <-> Compétence (Type: offre/besoin, niveau, description)
│       ├── ExchangeRequest.php  # Modèle Demande d'échange (Expéditeur, Destinataire, Statut)
│       ├── Conversation.php     # Modèle Conversation privée entre 2 utilisateurs
│       └── Message.php          # Modèle Message de chat avec statut de lecture
├── config/                      # Fichiers de configuration Laravel (auth, database, session, mail...)
├── database/
│   ├── migrations/              # Fichiers de structure de la base de données
│   └── seeders/
│       ├── CategorySeeder.php   # Seeder des catégories par défaut
│       ├── SkillSeeder.php      # Seeder des compétences par défaut
│       ├── DemoUsersSeeder.php  # Seeder des utilisateurs de démonstration et offres/besoins
│       └── DatabaseSeeder.php   # Seeder principal
├── public/                      # Point d'entrée web (index.php) et assets compilés
├── resources/
│   ├── css/ & js/               # Code source TailwindCSS et scripts JS/Vite
│   └── views/
│       ├── admin/               # Vues Blade de l'administration (dashboard, users, categories, requests, user-skills, login)
│       ├── auth/                # Vues Blade d'authentification client (login, register, forgot-password)
│       ├── chat/                # Vues Blade de la messagerie instantanée (index, show)
│       ├── exchange-requests/   # Vue de gestion des demandes d'échange
│       ├── members/             # Vues d'exploration de l'annuaire des membres
│       ├── user-skills/         # Vue de gestion de ses offres et besoins
│       ├── dashboard.blade.php  # Fil d'actualité et tableau de bord principal client
│       ├── welcome.blade.php    # Page d'accueil publique
│       └── layouts/             # Layouts Blade principaux (app.blade.php, admin.blade.php, navigation.blade.php)
├── routes/
│   ├── web.php                  # Routes principales de l'application (Membres & Administration)
│   ├── auth.php                 # Routes d'authentification Breeze
│   └── console.php              # Commandes CLI personnalisées
├── storage/                     # Fichiers de logs, de session et d'upload
└── vite.config.js               # Configuration du bundler Vite.js
```

---

## 🎬 Scénario d'Utilisation Réel

### 🔹 Scénario Côté Client (Utilisateur / Membre)
1. **Inscription / Connexion** : Un nouvel utilisateur (ex: Bruno) crée un compte ou se connecte. Il complète son profil en indiquant sa ville (*Toamasina*) et sa biographie.
2. **Définition de ses compétences** :
   - Bruno se rend sur **"Mes compétences"**.
   - Il ajoute une **Offre** : *Guitare*, niveau *Expert*, avec la description *"Cours de guitare débutant/intermédiaire"*.
   - Il ajoute un **Besoin** : *Laravel*, pour créer son premier projet web.
3. **Exploration du Fil d'Actualité & Recherche** :
   - Depuis son **Tableau de Bord**, Bruno parcourt le fil d'actualité et voit l'offre publiée par Alice : *"Je propose de l'aide sur Laravel"*.
   - Il peut aussi utiliser la barre de recherche pour chercher des membres à Antananarivo proposant Laravel.
4. **Envoi d'une Demande d'Échange** :
   - Bruno clique sur l'offre d'Alice et lui envoie une demande d'échange avec le message : *"Salut Alice, je voudrais apprendre Laravel. On peut échanger contre des cours de guitare ou d'anglais ?"*.
5. **Réception et Acceptation par le Destinataire** :
   - Alice se connecte, voit une notification dans l'onglet **"Demandes d'échange"**.
   - Elle consulte le profil de Bruno et clique sur **"Accepter"**.
6. **Discussion en Temps Réel** :
   - Dès que la demande est acceptée, Alice est automatiquement redirigée vers la **Messagerie (Chat)**.
   - Alice et Bruno peuvent désormais discuter en direct, fixer les horaires de leurs sessions d'échange et échanger leurs contacts.

---

### 🔹 Scénario Côté Administration (Administrateur)
1. **Connexion Sécurisée** : L'administrateur se rend sur `/admin/login` et saisit les identifiants d'administration.
2. **Analyse du Tableau de Bord** : L'administrateur consulte les métriques clés de la plateforme (nombre d'utilisateurs inscrits, demandes en attente, compétences publiées).
3. **Modération du Catalogue** :
   - Un utilisateur ayant ajouté la compétence *"Jonglage"* dans la catégorie *"Sport"*, l'administrateur peut valider, organiser ou supprimer des catégories/compétences inutiles depuis la section **"Catégories & Compétences"**.
4. **Modération des publications et demandes** :
   - Dans la section **"Offres & Besoins"**, l'administrateur passe en revue les publications récentes pour s'assurer qu'aucune annonce suspecte ou inappropriée n'est mise en ligne.
   - Dans la section **"Demandes"**, il peut vérifier l'activité globale et supprimer des demandes indésirables si un comportement inapproprié est signalé.
5. **Gestion des Utilisateurs** :
   - En cas d'abus ou de faux profil, l'administrateur recherche l'utilisateur dans l'onglet **"Utilisateurs"** et peut supprimer le compte.

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
