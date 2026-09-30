<?php
// Récupérer la liste des JS triés par nom
$sql = "SELECT id_produit, nom_de_produit
        FROM produit
        WHERE type_produit LIKE '%soci%'
        ORDER BY nom_de_produit";

$produits = $pdo->query($sql)->fetchAll();
?>

<h1>Jeux de société (<?= count($produits) ?> JS)</h1>
<div class="cards">
  <?php foreach ($produits as $produit): ?>
    <article class="card">
      <h2><?= htmlspecialchars($produit["nom_de_produit"]) ?></h2>

      <div class="actions">

<!-- Détails -->
<a href="index.php?page=produit-details&amp;id_produit=<?= $produit['id_produit'] ?>" class="btn">Détails</a>

<!-- Insérer (POST) -->
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
    <form method="post" action="index.php?page=produit-create" style="display:inline;">
    <button type="submit" style="background:none;border:none;padding:0;color:inherit;text-decoration:underline;cursor:pointer;">
    inserer
    </button>
    </form>
<?php endif ?>

<!-- Modifier (POST) -->
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
    <form method="post" action="index.php?page=produit-edit" style="display:inline;">
        <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
        <button type="submit" style="background:none;border:none;padding:0;color:inherit;text-decoration:underline;cursor:pointer;">
        Modifier
        </button>
    </form>
<?php endif ?>

<!-- Supprimer (POST) -->
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>
    <form method="post" action="index.php?page=produit-delete" style="display:inline;"
        onsubmit='return confirm(<?= json_encode("Voulez-vous supprimer " . $produit['nom_de_produit'] . " ?") ?>)'>
        <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
        <button type="submit" style="background:none;border:none;padding:0;color:inherit;text-decoration:underline;cursor:pointer;">
        supprimer
        </button>
    </form>
<?php endif ?>
</div>    
    </article>
  <?php endforeach ?>
</div>