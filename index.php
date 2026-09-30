<?php

session_start();

// créer les routes:

$routes = [

    '' => [
      'file' => 'pages/home.php',
      'title' => 'Accueil'
    ],
    
    'produit' => [
    'file' => 'pages/produit/list.php',
    'title' => 'Liste des produits',
    'roles' => ['user', 'admin'],
    ],


    'produit-details' => [
    'file' => 'pages/produit/details.php',
    'title' => 'detail du produit',
    'roles' => ['user', 'admin'],
    ],

     // créer les routes pour CD ,DVD,  JS et JV:

    'cd' => [
    'file' => 'pages/produit/cd.php',
    'title' => 'Liste des CD',
    'roles' => ['user', 'admin'],
    ],


    'dvd' => [
    'file' => 'pages/produit/dvd.php',
    'title' => 'Liste des DVD',
    'roles' => ['user', 'admin'],
    ],

    'js' => [
    'file' => 'pages/produit/js.php',
    'title' => 'Liste des JS',
    'roles' => ['user', 'admin'],
    ],

    'jv' => [
    'file' => 'pages/produit/jv.php',
    'title' => 'Liste des JV',
    'roles' => ['user', 'admin'],
    ],
    
    // créer les routes pour créer , supprimer, et modier un produit:

    'produit-create' => [
      'file' => 'pages/produit/create.php',
      'title' => 'Création d\'un produit', 
      'roles' => ['admin'],
    ],
  
    'produit-delete' => [
      'file' => 'pages/produit/produit-delete.php',
      'title' => 'Suppression d\'un produit',
      'roles' => ['admin'],
    ],
  
    'produit-edit' => [
      'file' => 'pages/produit/produit-update.php',
      'title' => 'Modification d\'un produit',
      'roles' => ['admin'],
    ],
  

  /**
   * Gestion de l'authentification
   */

   'register' => [
    'file' => 'pages/auth/register.php',
    'title' => 'S\'enregistrer',
    'roles' => ['admin'],
  ],

  'login' => [
    'file' => 'pages/auth/login.php',
    'title' => 'Se connecter',
    'roles' => ['admin'],
  ],

  'logout' => [
    'file' => 'pages/auth/logout.php',
    'title' => 'Se déconnecter',
    'roles' => ['admin'],
  ],










];

// si page récupéré ou route récupéré n'existe pas, affiché " not found"

$page = $_GET['page'] ?? '';
$route = $routes[$page] ?? null;

if ($route === null) {
    $route = [
      'file' => 'pages/errors/not-found.php',
      'title' => "404 not found"
    ];
}


// pour montrer non l'accès user 
$requiredRoles = $route['roles'] ?? null;
if ($requiredRoles !== null) {

  if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
  }

  if (!in_array($_SESSION['user']['role'], $requiredRoles)) {
    $route = [
      'file' => 'pages/errors/forbidden.php',
      'title' => "403 forbidden"
    ];
  }
}





$file = $route["file"];
$title = $route["title"];

require_once 'config/database.php';


// Assembler les parties header et footer 

require_once 'partials/header.php';
require_once $file;  
require_once 'partials/footer.php';

?>