<?php
//  Récupérer l'id depuis l'URL
$id_produit = filter_input(INPUT_GET, 'id_produit', FILTER_VALIDATE_INT);
$produit = false;

//  Vérifier si l'id est valide
if ($id_produit !== null && $id_produit !== false) {


// Récupérer les détail du produit depuis SQL avec id produit
$sql = "SELECT  p.id_produit,
                p.type_produit,
                p.nom_de_produit,
                p.niveau_primaire,
                p.prix
        FROM    produit AS p
        WHERE   p.id_produit = ?";

$request = $pdo->prepare($sql);
$request->execute([$id_produit]);
// Récupérer une seule ligne du tableau associatif
$produit = $request->fetch();
 
}
?>



<!--présenter un produit avec tous les détails 
presenter de facon en carte
-->


<?php if (!$produit) : ?>
  <?php http_response_code(404); ?>
  <h1>Produit introuvable</h1>
  <p>Aucun produit ne correspond à l'id "<?= htmlspecialchars($id_produit) ?>".</p>


<?php else : ?>

  <div class="card-produit">
    <h1><?= htmlspecialchars($produit['nom_de_produit']) ?></h1>
    <p class="badge"><?= htmlspecialchars($produit['type_produit']) ?></p>

    <p class="prix"><?= number_format($produit['prix'], 2, ',', ' ') ?> &euro;</p>

    <p class="niveau"> Niveau : <?= htmlspecialchars($produit['niveau_primaire']) ?></p>

    <a href="?page=produit" class="btn btn-back">← Retour à la liste</a>

</div>
<?php endif ?>