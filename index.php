<?php
session_start();

if (isset($_SESSION['user']))
{
  header('location: membre.php');
}
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
  <h2 id="title_connexion">CONNEXION</h2>
  <form action="connexion.php" method="post">
    nom d'utilisateur :<br>
    <input type="text" name="user"><br>
    mot de passe :<br>
    <input type="password" name="pwd"><br>
    <input type="submit" name="submit" value="Valider">
  </form>
  <br>
  <a id="go_signin" href="inscription.php">Pas encore inscrit ?</a>
  <br>
  <?php
   if ($_GET['error'] == 1) {
     echo "<div style='color:red'>Erreur dans les identifiants</div>";
   }
   elseif ($_GET['error'] == 2) {
     echo "<div style='color:red'>Erreur dans le mot de passe</div>";
   }
   ?>
</main>
</body>
</html>
