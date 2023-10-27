# Snowtricks

Site développé en from scratch depuis le frameword Symfony version 6.3.6. avec l'ORM (Mapping objet relationnel) Doctrine et le moteur de template TWIG.
Languages utilisés : PHP 8.2.4, Javascript

## DESCRIPTION DU PROJET

Snowtricks est un site collaboratif proposé par Jimmy Sweat et créée pour faire connaître le snowboard au grand public et aider à l'apprentissage des figures (tricks).

## PRÉREQUIS

- PHP 8.2.4
- Javascript
- MySQL ou Mariadb
- Composer version 2.3.10
- Symfony CLI 5.6
- Symfony 6.3.6

## INSTALLATION

1. Clôner le projet depuis mon compte gitHub sur votre disque dur.
2. Ouvrez le projet dans votre éditeur de texte.
3. Installer les dépendances Composer.```composer install```
4. Noter que le dossier vendor et les fichiers composer.json, composer.lock sont générés automatiquement par composer.
5. Créer un copie du fichier .env et le renommer .env.local
6. Renseigner les informations de connexion à la base de données, de votre SGBDR depuis le fichier .env.local
   - ex : `DATABASE_URL="mysql://votre-identifiant:votre-mot-de-passe@127.0.0.1.3306/nom-de-la-bd-snowtricks?serverVersion=mariadb-10.4"`
7. Créer la base de données : `symfony console doctrine:database:create`
8. Jouer la dernière migration : `symfony console doctrine:migrations:migrate`
9. Charger les fixtures : `symfony console doctrine:fixtures:load`

## DOCUMENTATION À UTILISER

[Documentation Symfony](https://symfony.com/doc/current/index.html)

[Documentation PHP](https://www.php.net/docs.php)

[Documentation Doctrine](https://www.doctrine-project.org/projects/doctrine-orm/en/2.16/index.html)

[Documentation Twig](https://twig.symfony.com/doc/)

## COMMENT CONTRIBUER

Vous pouvez contribuer, en ajoutant de nouvelles figures de snowboard, après avoir créer votre compte utilisateur.
N'hésitez pas à les ajouter depuis la page de création. Participez aux discussions autour des figures de snowboard, pour partager vos expériences et aider les autres snowboarders.
