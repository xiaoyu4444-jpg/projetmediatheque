<nav>

    <h1>Médiathèque enfants</h1>

    <ul>
        <li><a href="index.php">🏠 Home</a></li>
        <li><a href="index.php?page=produit">Tous les produits</a></li>
    </ul>

    <ul class="menu-categories">
        <li><a href="index.php?page=cd">💿 CD à ecouter</a></li>
        <li><a href="index.php?page=dvd">🎬 DVD film , Dessin animé</a></li>
        <li><a href="index.php?page=jv"> 🎮 Jeux vidéo</a></li>
        <li><a href="index.php?page=js"> 🎲 Jeux de société</a></li>
    </ul>


        
    
    <ul class="menu-categories">
      <?php if (!isset($_SESSION['user'])) : ?>
         <li><a href="index.php?page=login">Se connecter</a></li>
         <li><a href="index.php?page=register">S'inscrire</a></li>
      <?php else : ?>
         <li>Connecté : <?= $_SESSION['user']['email'] ?> (<?= $_SESSION['user']['role'] ?>)</li>
         <li><a href="index.php?page=logout">Se déconnecter</a></li>
      <?php endif ?>
    </ul>


</nav>




