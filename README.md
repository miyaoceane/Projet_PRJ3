#1PRJ3
#Système de Réservation pour Services Locaux

##Présentation du projet

Ce projet est un site web de réservation en ligne pour un salon de coiffure.
Il permet aux clients de réserver un rendez-vous 24h/24 directement depuis le site.

##Le projet comprend :

une page vitrine du salon
un système de réservation avec calendrier
un système d’administration
une base de données MySQL

##Objectif

Moderniser la prise de rendez-vous d’un salon de coiffure en permettant :

la réservation en ligne
la gestion automatique des créneaux
l’envoi d’emails de confirmation
la gestion des réservations par l’administrateur


##Technologies utilisées:

HTML5 (structure du site)
CSS3 (design + responsive)
JavaScript (ES6) (calendrier + créneaux dynamiques)
PHP 7+ (traitement du formulaire + sécurité)
MySQL (stockage des services et réservations)
Bootstrap 5 (mise en page responsive)


#Fonctionnalités du site
1. Page vitrine

La page d’accueil contient :
le nom du salon
une description du salon
les services proposés
les horaires d’ouverture
les coordonnées (adresse + téléphone)
des témoignages clients

Exemple :
Coupe homme – 30 min – 25€
Coupe femme – 45 min – 35€
Coloration – 1h30 – 70€

2. Système de réservation

Le client peut :
choisir un service
choisir une date dans un calendrier
choisir un créneau parmis les créneaux disponibles
remplir ses informations
confirmer la réservation

Informations demandées :
nom
prénom
email
téléphone
service choisi
date
heure

3. Gestion des créneaux

Le système :
affiche uniquement les créneaux disponibles
empêche la double réservation
gère les services avec des durées différentes
bloque automatiquement les créneaux déjà réservés

Exemple :
Si une coupe dure 45 minutes, le créneau suivant commence seulement après 45 minutes.

4. Notifications email

Après la réservation :

Le client reçoit un email avec :
le service choisi
la date
l’heure
son nom
un message de confirmation


5. Interface d’administration

L’administrateur peut :
se connecter avec un identifiant et un mot de passe
voir toutes les réservations
confirmer ou annuler une réservation


