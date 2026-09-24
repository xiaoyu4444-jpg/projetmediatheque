<?php

// créer les routes:

$routes = [

    '' => [
      'file' => 'pages/home.php',
      'title' => 'Accueil'
    ],
    
    'produit' => [
    'file' => 'pages/produit/list.php',
    'title' => 'Liste des produits',
    
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
  
$file = $route["file"];


require_once 'config/database.php';


// Assembler les parties header et footer 

require_once 'partials/header.php';
require_once $file;  
require_once 'partials/footer.php';

?>