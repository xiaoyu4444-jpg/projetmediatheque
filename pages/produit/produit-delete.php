<?php
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
  http_response_code(405);
  echo "<h1>Action non autorisée.</h1>";
  
}



$id_produit = filter_input(INPUT_POST, 'id_produit', FILTER_VALIDATE_INT);

if ($id_produit) {
    $sql = "DELETE FROM produit WHERE id_produit = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$id_produit]);
}

header("Location: index.php?page=produit");






