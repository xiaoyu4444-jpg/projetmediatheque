<?php

// créer les routes:
$route=[





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
$title = $route["title"];



// Assembler les parties header et footer 

require_once 'partials/header.php';

require_once 'partials/footer.php';

?>