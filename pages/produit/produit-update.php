<?php
// Récupérer l'ID (POST prioritaire, sinon GET)
$id = filter_input(INPUT_POST, 'id_produit', FILTER_VALIDATE_INT)
   ?: filter_input(INPUT_GET, 'id_produit', FILTER_VALIDATE_INT);

// Récupérer les niveaux primaires
$query = "SELECT id, libelle FROM niveau_primaire ORDER BY libelle";
$niveaux = $pdo->query($query)->fetchAll();

// Récupérer les types de produits
$query = "SELECT libelle FROM type_produit ORDER BY libelle";
$types_produits = $pdo->query($query)->fetchAll(PDO::FETCH_COLUMN);

// Récupérer le produit à modifier
$statement = $pdo->prepare("SELECT id_produit, nom_de_produit, type_produit, niveau_primaire, prix FROM produit WHERE id_produit = ?");
$statement->execute([$id]);
$produit = $statement->fetch();

if (!$produit) {
    header('Location: index.php?page=cd');
    exit;
}

// Initialisation des variables
$values = [
    'nom_de_produit'  => '',
    'type_produit'    => '',
    'niveau_primaire' => '',
    'PRIX'            => ''
];
$errors = [];


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $values['nom_de_produit']  = $produit['nom_de_produit'];
    $values['type_produit']    = $produit['type_produit'];
    $values['niveau_primaire'] = $produit['niveau_primaire'];
    $values['PRIX']            = $produit['prix'];
}


// Gérer le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mapping
    foreach (array_keys($_POST) as $field) {
        if (array_key_exists($field, $values)) {
            $values[$field] = trim($_POST[$field]) ?? '';
        }
    }
    // Validation des champs
    // 1. Nom
    if ($values['nom_de_produit'] === '') {
        $errors['nom_de_produit'] = "Le nom du produit est obligatoire.";
    } else if (strlen($values['nom_de_produit']) > 100) {
        $errors['nom_de_produit'] = "Le nom ne peut pas dépasser 100 caractères.";
    }

    // 2. Type
    if ($values['type_produit'] === '') {
        $errors['type_produit'] = "Le type de produit est obligatoire.";
    }

    // 3. Niveau
    if ($values['niveau_primaire'] === '') {
        $errors['niveau_primaire'] = "Le niveau primaire est obligatoire.";
    } else {
        $niveauxLibelles = array_column($niveaux, 'libelle');
        if (!in_array($values['niveau_primaire'], $niveauxLibelles)) {
            $errors['niveau_primaire'] = "Le niveau primaire n'existe pas.";
        }
    }

    // 4. Prix
    if ($values['PRIX'] !== '') {
        $price = filter_var($values['PRIX'], FILTER_VALIDATE_FLOAT);
        if (!$price || $price < 0) {
            $errors['PRIX'] = "Le prix doit être positif.";
        }
    }

    // Modification d'un produit en base de donée
    if (!$errors) {
        try {
            $sql = "UPDATE produit 
                    SET 
                    nom_de_produit = ?, 
                    type_produit = ?, 
                    niveau_primaire = ?,
                    prix = ?
                    WHERE id_produit = ?";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                $values['nom_de_produit'],
                $values['type_produit'],
                $values['niveau_primaire'],
                $values['PRIX']            !== '' ? $values['PRIX']            : null,
                $id
            ]);

 
            header('Location: index.php?page=cd');
            exit;

        } catch (PDOException $e) {
            $errors['nom_de_produit'] = "Erreur lors de la modification : " . $e->getMessage();
        }
    }
}
?>

<h1>Modification d'un produit</h1>

<form method="post">
    <input type="hidden" name="id_produit" value="<?= $id ?>">

    <div>
        <label for="nom_de_produit">Nom de produit: *</label>
        <input type="text" name="nom_de_produit" id="nom_de_produit" value="<?= htmlspecialchars($values['nom_de_produit']) ?>">
        <?php if (isset($errors['nom_de_produit'])) : ?>
            <span class="error"><?= $errors['nom_de_produit'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="type_produit">Type de produit: *</label>
        <select name="type_produit" id="type_produit">
            <option value=""> --- Sélectionner un type --- </option>
            <?php foreach ($types_produits as $t) : ?>
                <option value="<?= htmlspecialchars($t) ?>" <?= $values["type_produit"] === $t ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t) ?>
                </option>
            <?php endforeach ?>
        </select>
        <?php if (isset($errors['type_produit'])) : ?>
            <span class="error"><?= $errors['type_produit'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="niveau_primaire">Niveau primaire: *</label>
        <select name="niveau_primaire" id="niveau_primaire">
            <option value=""> --- Sélectionner un niveau --- </option>
            <?php foreach ($niveaux as $n) : ?>
                <option value="<?= htmlspecialchars($n["libelle"]) ?>" <?= $values["niveau_primaire"] === $n["libelle"] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($n["libelle"]) ?>
                </option>
            <?php endforeach ?>
        </select>
        <?php if (isset($errors['niveau_primaire'])) : ?>
            <span class="error"><?= $errors['niveau_primaire'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="PRIX">Prix:</label>
        <input type="number" min="0" step="0.001" name="PRIX" id="PRIX" value="<?= $values['PRIX'] ?>">
        <?php if (isset($errors['PRIX'])) : ?>
            <span class="error"><?= $errors['PRIX'] ?></span>
        <?php endif ?>
    </div>

    <button>Enregistrer les modifications</button>

    <p>* champ obligatoire</p>
</form>