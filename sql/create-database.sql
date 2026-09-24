/* =============================================================================
   Création de la base de démonstration PHP / PDO / SQL Server
   -----------------------------------------------------------------------------
   Exécution :
     - SSMS / Azure Data Studio : ouvrir le fichier et exécuter (F5)
     - Ligne de commande :
         sqlcmd -S "(localdb)\MSSQLLocalDB" -E -i create-database.sql
         sqlcmd -S localhost -E -i create-database.sql
   ============================================================================= */
/* -----------------------------------------------------------------------------
   1. Base de données
   -------------------------------------------------------------------------- */
USE master;


GO
-- Le script est réexécutable : on repart d'une base propre.
-- SINGLE_USER ... ROLLBACK IMMEDIATE ferme les connexions en cours,
-- sans quoi le DROP échouerait.
IF DB_ID('mediatheque') IS NOT NULL
    BEGIN
        ALTER DATABASE mediatheque
            SET SINGLE_USER 
            WITH ROLLBACK IMMEDIATE;
        DROP DATABASE mediatheque;
    END


GO
CREATE DATABASE mediatheque;


GO
/* -----------------------------------------------------------------------------
   2. Connexion (login) et utilisateur (user)
   -----------------------------------------------------------------------------
   Différence essentielle avec MySQL : SQL Server sépare deux notions.

     LOGIN  -> niveau SERVEUR   : permet de se connecter à l'instance
     USER   -> niveau BASE      : permet d'accéder à une base précise

   Un LOGIN sans USER peut se connecter mais ne voit aucune base.
   -------------------------------------------------------------------------- */
USE master;


GO
IF SUSER_ID('mediatheque_user') IS NOT NULL
    DROP LOGIN mediatheque_user;


GO
CREATE LOGIN mediatheque_user
    WITH PASSWORD = 'Test1234=', DEFAULT_DATABASE = mediatheque, CHECK_POLICY = OFF;


GO
USE mediatheque;


GO
CREATE USER mediatheque_user FOR LOGIN mediatheque_user;


GO
-- Droits minimaux : lecture + écriture sur les données.
-- On évite db_owner, qui donnerait tous les droits (dont DROP TABLE).
ALTER ROLE db_datareader ADD MEMBER mediatheque_user;

ALTER ROLE db_datawriter ADD MEMBER mediatheque_user;


GO
/* -----------------------------------------------------------------------------
   3. Tables liées (relation 1-N : un auteur écrit plusieurs livres)
   -------------------------------------------------------------------------- */
USE mediatheque;


GO
-- Ordre de suppression inverse de l'ordre de création :
-- la table qui porte la clé étrangère part en premier.
DROP TABLE IF EXISTS dbo.possede;
DROP TABLE IF EXISTS dbo.produit;
DROP TABLE IF EXISTS dbo.users;

CREATE TABLE users(
   id_utilisateur INT IDENTITY  ,
   email VARCHAR(150) ,
   password VARCHAR(255) ,
   PRIMARY KEY(id_utilisateur)
);

CREATE TABLE produit(
   id_produit INT IDENTITY ,
   type_produit VARCHAR(50) ,
   nom_de_produit VARCHAR(100) ,
   niveau_primaire VARCHAR(50) ,
   prix SMALLMONEY,
   PRIMARY KEY(id_produit)
);

CREATE TABLE possede(
   id_utilisateur INT ,
   id_produit INT ,
   PRIMARY KEY(id_utilisateur, id_produit),
   FOREIGN KEY(id_utilisateur) REFERENCES users(id_utilisateur),
   FOREIGN KEY(id_produit) REFERENCES produit(id_produit)
);


GO

INSERT  INTO dbo.produit (
    nom_de_produit,
    type_produit,
    niveau_primaire,
    prix
    
)
VALUES

/*
 1. CD À ÉCOUTER (8 produits)
*/
(N'Les Comptines de la Ferme', N'CD', N'Maternelle (PS-MS)', 0.20),
(N'Le Grand Voyage de Petit Ours', N'CD', N'Maternelle (MS-GS)', 0.20),
(N'Éveil Musical : Les Instruments du Monde', N'CD', N'Maternelle (PS-MS)', 0.20),
(N'Contes classiques : Le Petit Chaperon Rouge', N'CD', N'CP-CE1', 0.20),
(N'Chansons pour apprendre l''alphabet', N'CD', N'Maternelle (GS)', 0.20),
(N'Histoires de Noël pour les enfants', N'CD', N'CP-CE1', 0.20),
(N'Relaxation et Sophrologie pour enfants', N'CD', N'Tous niveaux', 0.20),
(N'Les Fables de La Fontaine en musique', N'CD', N'CE1-CE2', 0.20),
/*
 2. DVD À REGARDER (8 produits)
*/
(N'C''est pas sorcier : Le Corps Humain', N'DVD', N'CE2-CM1', 0.20),
(N'Les Aventures de Tchoupi', N'DVD', N'Maternelle (PS-MS)', 0.20),
(N'Il était une fois... la Vie', N'DVD', N'CE1-CE2', 0.20),
(N'Kirikou et la Sorcière', N'DVD', N'Maternelle (GS)-CP', 0.20),
(N'Le Petit Nicolas', N'DVD', N'CE2-CM1', 0.20),
(N'Documentaire : Les Animaux de la Savane', N'DVD', N'CP-CE1', 0.20),
(N'Peppa Pig : Les Saisons', N'DVD', N'Maternelle (PS-MS)', 0.20),
(N'Apprendre l''anglais avec Muzzy', N'DVD', N'CE1-CE2', 0.20),
/*
3. JEUX VIDÉO POUR APPRENDRE LE FRANÇAIS (7 produits)
*/
(N'Lettres et Sons : Apprendre à lire', N'Jeu vidéo', N'Maternelle (GS)-CP', 0.20),
(N'Vocabulaire Junior : La Maison', N'Jeu vidéo', N'CP-CE1', 0.20),
(N'Conjugaison Facile : Le Présent', N'Jeu vidéo', N'CE2-CM1', 0.20),
(N'Orthographe : Les Accords Parfaits', N'Jeu vidéo', N'CM1-CM2', 0.20),
(N'Dictée Magique', N'Jeu vidéo', N'CE1-CE2', 0.20),
(N'Grammaire en Folie', N'Jeu vidéo', N'CE2-CM1', 0.20),
(N'Lecture Rapide : Les Syllabes', N'Jeu vidéo', N'CP', 0.20),
/*
 4. JEUX DE SOCIÉTÉ (7 produits)
*/
(N'Le Loto des Lettres', N'Jeu de société', N'Maternelle (GS)-CP', 0.20),
(N'Vocabulo : La Bataille des Mots', N'Jeu de société', N'CE1-CE2', 0.20),
(N'Conjugaison : Le Jeu des 7 Familles', N'Jeu de société', N'CE2-CM1', 0.20),
(N'L''Orthographe en S''amusant', N'Jeu de société', N'CM1-CM2', 0.20),
(N'Devine Tête : Les Animaux', N'Jeu de société', N'Maternelle (GS)-CP', 0.20),
(N'Scrabble Junior', N'Jeu de société', N'CP-CE1', 0.20),
(N'L''Atelier des Mots', N'Jeu de société', N'CE2-CM1', 0.20);
GO

