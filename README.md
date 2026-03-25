# 1PRJ3
# Système de Réservation pour Services Locaux

## Présentation du projet

Ce projet est un site web de réservation en ligne pour un salon de coiffure.
Il permet aux clients de réserver un rendez-vous 24h/24 directement depuis le site.

## Le projet comprend :

- une page vitrine du salon
- un système de réservation avec calendrier interractif
- un système d’administration
- une base de données MySQL

## Objectif

Moderniser la prise de rendez-vous d’un salon de coiffure en permettant :

- la réservation en ligne
- la gestion automatique des créneaux
- l’envoi d’emails de confirmation
- la gestion des réservations par l’administrateur


## Technologies utilisées:

- HTML5 (structure du site)
- CSS3 (design + responsive)
- JavaScript (ES6) (calendrier + créneaux dynamiques)
- PHP 7+ (traitement du formulaire + sécurité)
- MySQL (stockage des services et réservations)
- Bootstrap 5 (mise en page responsive)


# Fonctionnalités du site
## 1. Page vitrine

La page d’accueil contient :
- le nom du salon
- une description du salon
- les services proposés
- les horaires d’ouverture
- les coordonnées (adresse + téléphone)
- des témoignages clients

Exemple :
```
Coupe homme – 30 min – 25€
Coupe femme – 45 min – 35€
Coloration – 1h30 – 70€
```

## 2. Système de réservation

Le client peut :
- choisir un service
- choisir une date dans un calendrier
- choisir un créneau parmis les créneaux disponibles
- remplir ses informations
- confirmer la réservation

Informations demandées :
- nom
- prénom
- email
- téléphone
- service choisi
- date
- heure

 ## 3. Gestion des créneaux

Le système :
- affiche uniquement les créneaux disponibles
- empêche la double réservation
- gère les services avec des durées différentes
- bloque automatiquement les créneaux déjà réservés

Exemple :
Si une coupe dure 45 minutes, le créneau suivant commence seulement après 45 minutes.

## 4. Notifications email

Après la réservation :

Le client reçoit un email avec :
- le service choisi
- la date
- l’heure
- son nom
- un message de confirmation


  ## 5. Interface d’administration

L’administrateur peut :
- se connecter avec un identifiant et un mot de passe
- voir toutes les réservations
- confirmer ou annuler une réservation


## Structure du projet

```

Projet_PRJ3/

├── ADMIN/
│   ├── Admin.php        #Interface Administration
│   └── connexion.php    #Connexion à l'interface
├── asset/
│   ├── css/
│   │   └── style.css    #tout le style
│   └── js/
│       └── script.js    #tout le script : validation, calendrier intérractif
├── Config/
│   └── config.php       #connexion à la BDD
├── Debug/
│   └── debug.php        #Aide pour le deboggage
├── INC/
│   ├── footer.inc.php   #le footer de toutes les pages (fonction réutilisable)
│   └── header.inc.php   #fonction réutilisable
├── Pages/
│   ├── acceuil.php      #page vitrine
│   └── reservation.php  #page de réservation
├── .gitignore           
└── index.php            #appele tous les autres fichiers 

```

## Structure de la base de données

### Table 'service'
```sql
CREATE TABLE service (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    description TEXT,
    duree_minute INT,
    prix_euros DECIMAL(10,0)
);
```
### Table 'reservation'
```sql
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT,
    date_rdv DATE,
    heure_rdv TIME,
    nom_client VARCHAR(100),
    prenom_client VARCHAR(100)
    email_client VARCHAR(100),
    telephone VARCHAR(20),
    statut ENUM('attente', 'confirme', 'annule');
);
```
### Table 'disponibilites'
```sql
CREATE TABLE disponibilites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour_semaine INT,
    heure_debut TIME,
    heure_fin TIME,
    actif BOOLEAN
);

INSERT INTO disponibilites (jour_semaine, heure_debut, heure_fin, actif) VALUES
(1, '09:00:00', '18:00:00', 1),
(2, '09:00:00', '18:00:00', 1),
(3, '09:00:00', '13:00:00', 1),
(4, '09:00:00', '19:30:00', 1),
(5, '09:00:00', '19:30:00', 1),
(6, '10:00:00', '17:00:00', 1),
(0, '00:00:00', '00:00:00', 0);

```
## Sécurité
- htmlspecialchars() pour protéger contre XSS
- intval() pour sécuriser les nombres
- trim() pour nettoyer les champs
- Requêtes préparées PDO pour éviter les injections SQL
- Validation côté client (JS) et côté serveur (PHP)


## Améliorations possibles
- Ajouter un système de paiement en ligne
- Ajouter des comptes clients
- Envoyer des SMS de confirmation
- Ajouter un calendrier côté admin
- Permettre la modification d’un rendez-vous

## Tests effectués

- Gestion des réservation depuis la page Admin
- Connexion à la page Admin
- Validation des emails et numéro de téléphone
- Gestion des erreurs 
- Insertion dans la base de données
- Calcul correct des créneaux


## Équipe

- **Kouadio Océane** - Développeur principal
- **Zoumana Laetitia** - Développeur


  
### Répartition des tâches

- **Kouadio Océane** :Création du projet, fichier README.md, style : page d'accueil-page de connexion-page de reservation-page Admin, vérification et validation php (côté serveur),script.js,accueil.php, connexion.php, reservation.php, Admin.php, gestion des erreurs
   
- **Zoumana Laetitia** : Personnas, création de la base de données, style page d'accueil, fonction debug, code header, code footer, code html page d'accueil, page connexion, gestions des erreurs


## Licence

Projet étudiant - École IT - 2025-2026

## Contact

Pour toute question : 111916@ecole-it.com

