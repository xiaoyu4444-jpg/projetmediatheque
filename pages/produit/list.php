

<?php
// Récupérer la liste des produits triés par nom de produit


$sql = "SELECT id_produit, nom_de_produit
        FROM produit
        ORDER BY nom_de_produit";

$produit = $pdo->query($sql)->fetchAll();


?>

<!-- présenter la liste de produits -->
 
<h1>  Tous les produits</h1>

<?php
// pages/produit/list.php

$sql = "SELECT id_produit, nom_de_produit 
        FROM produit 
        ORDER BY nom_de_produit";

$produit = $pdo->query($sql)->fetchAll();
?>

<h1 class="titre-page">Tous les produits</h1>

<div class="grille-cartes">
    <?php foreach ($produit as $p): ?>
        <div class="carte">
            <?= htmlspecialchars($p['nom_de_produit']) ?>
        </div>
    <?php endforeach; ?>
</div>

