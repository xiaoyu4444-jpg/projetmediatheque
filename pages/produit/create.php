<?php




// Récupérer les niveaux primaires

$query = "SELECT id, libelle FROM niveau_primaire ORDER BY libelle";
$niveaux = $pdo->query($query)->fetchAll();

// Récupérer les types de produits
$query = "SELECT libelle FROM type_produit ORDER BY libelle";
$types_produits = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN);


//  Initialisation des variables
$values = [
    'nom_de_produit' => '',
    'type_produit' => '',
    'niveau_primaire' => '',
    'PRIX' => ''
];
$errors = [];


// Gérer le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mapping de données
    foreach (array_keys($_POST) as $field) {
        $values[$field] = trim($_POST[$field]) ?? '';
    }




// VALIDATION DES CHAMPS(1à4):


// 1. Nom du produit 

if ($values['nom_de_produit'] === '') {
        $errors['nom_de_produit'] = "Le nom du produit est obligatoire.";
    } else if (strlen($values['nom_de_produit']) > 100) {
        $errors['nom_de_produit'] = "Le nom ne peut pas dépasser 100 caractères.";
    }

// 2. Type de produit
if ($values['type_produit'] === '') {
    $errors['type_produit'] = "Le type de produit est obligatoire.";
}

// 3. Niveau primaire 
 
if ($values['niveau_primaire'] === '') {
    $errors['niveau_primaire'] = "Le niveau primaire est obligatoire.";
}
else {
    $niveauxLibelles = array_column($niveaux, 'libelle');
    if (!in_array($values['niveau_primaire'], $niveauxLibelles)) {
    $errors['niveau_primaire'] = "Le niveau primaire n'existe pas.";
    }
}

// 4. Prix 

if ($values['PRIX'] !== '') {
    $price = filter_var($values['PRIX'], FILTER_VALIDATE_FLOAT);
    if (!$price||$price < 0) {
        $errors['PRIX'] = "Le prix doit être positif.";
    }
}



// Insérer dans la base de deonné:

if (!$errors) {
    try {
        $sql = "
            INSERT INTO produit (nom_de_produit, type_produit, niveau_primaire, prix)
            VALUES (?, ?, ?, ?)
        ";

        $statement = $pdo->prepare($sql);
        $statement->execute([
            $values['nom_de_produit'],
            $values['type_produit'] !== '' ? $values['type_produit'] : null,
            $values['niveau_primaire'] !== '' ? $values['niveau_primaire'] : null,
            $values['PRIX'] !== '' ? $values['PRIX'] : null,
        ]);

        // Redirection vers la liste des CD
        header('Location: index.php?page=cd');
        exit;

    } catch (PDOException $e) {
        $errors['nom_de_produit'] = "Erreur lors de l'insertion : " . $e->getMessage();
    }
}
}

?>






<!-- pour ajouter des nouveaux produits -->
 
<h1>Insertion d'un produit</h1>


<form method="post">
    <!-- dans le tableau, c'est le champs pour inserer le nom de produit-->
    
    <div>
    <label for="nom_de_produit">Nom de produit: *</label>
    <input type="text" name="nom_de_produit" id="nom_de_produit"  value="<?= htmlspecialchars($values['nom_de_produit']) ?>">
    <?php if (isset($errors['nom_de_produit'])) : ?>
      <span class="error"><?= $errors['nom_de_produit'] ?></span>
    <?php endif ?>
    </div>

  <!-- dans le tableau, c'est le champs pour inserer le type de produit -->
  
  <div>
    <label for="type_produit">Type de produit: *</label>
    <select name="type_produit" id="type_produit" >
      <option value=""selected> --- Sélectionner un type --- </option>
      <?php foreach ($types_produits as $t) : ?>
        <option 
          value="<?= htmlspecialchars($t) ?>"
          <?= $values["type_produit"] === $t ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($t) ?>
        </option>
      <?php endforeach ?>
    </select>
    <?php if (isset($errors['type_produit'])) : ?>
      <span class="error"><?= $errors['type_produit'] ?></span>
    <?php endif ?>
  </div>


  <!--  dans le tableau, c'est le champs pour inserer le niveau primaire de produit -->
  

  <div>
    <label for="niveau_primaire">Niveau primaire: *</label>
    <select name="niveau_primaire" id="niveau_primaire" >
      <option value=""selected> --- Sélectionner un niveau --- </option>
      <?php foreach ($niveaux as $n) : ?>
        <option 
          value="<?= htmlspecialchars($n["libelle"]) ?>"
          <?= $values["niveau_primaire"] === $n["libelle"] ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($n["libelle"]) ?>
        </option>
      <?php endforeach ?>
    </select>
    <?php if (isset($errors['niveau_primaire'])) : ?>
      <span class="error"><?= $errors['niveau_primaire'] ?></span>
    <?php endif ?>
  </div>
  



  <!--  dans le tableau, c'est le champs pour inserer le prix de produit -->
  
  <div>
    <label for="PRIX">Prix:</label>
    <input type="number" min="0" step="0.001" name="PRIX" id="PRIX" value="<?= $values['PRIX'] ?>">
    <?php if (isset($errors['PRIX'])) : ?>
      <span class="error"><?= $errors['PRIX'] ?></span>
    <?php endif ?>
  </div>





  <button>Créer un nouveau produit</button>

  <p>* champ obligatoire</p>
</form>