

<?php
// Récupérer la liste des produits triés par nom de produit


$sql = "SELECT id_produit, nom_de_produit
        FROM produit
        ORDER BY nom_de_produit";

$produits = $pdo->query($sql)->fetchAll();


?>

<!-- présenter la liste de produits -->
 
<h1>  Tous les produits (<?=count($produits) ?> produits) </h1>








<div class="cards">

  <?php foreach ($produits as $produit): ?>

    <article class="card">
      <h2><?= $produit["nom_de_produit"] ?></h2>

   

    </article>

  <?php endforeach ?>

</div>
