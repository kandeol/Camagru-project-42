<?php
session_start();

?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
  <meta http-equiv="content-type" content="text/css">
  <title>Ma première page avec du style</title>
  <link type="text/css" rel="stylesheet" href="/css/index.css" media="all"/>
</head>
<body>
  <header>
    <h1 class="title"> CAMAGRU </h1>
  </header>
<!-- Menu de navigation du site -->
<div id="bar_nav">
  <ul>
    <li><a href="membre.php">Montage</a></li>
    <li><a href="gallery.php">Gallery</a></li>
    <?php
    if ($_SESSION['user']) {
      echo "<li><a href='deconnexion.php'>Deconnexion</a></li>";
    }
    else {
      echo "<li><a href='index.php'>Connexion</a></li>";
    }
     ?>
  </ul>
</div>
<main>

  <br>
  <br>
  <?php
   if ($_GET['sit'] == 1) {
     echo "<div style='color:green'>Bienvenue , votre compte est confirmer !</div>";
   }elseif ($_GET['sit'] == 2) {
     echo "<div style='color:green'>Compte deja confirmer</div>";
   }elseif ($_GET['sit'] == 3) {
     echo "<div style='color:red'>Erreur : pas d'utilisateur enregistrer</div>";
   }
   ?>
   <a id="go_signin" href="index.php">Retour vers la page d'accueil ?</a>

</main>
</body>
</html>
