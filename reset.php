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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8" />
  <meta http-equiv="content-type" content="text/css">
  <title>Ma première page avec du style</title>
  <link type="text/css" rel="stylesheet" href="/css/index.css" media="all" />
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

    <div>reinitialiser son mot de passe</div>
    <br>
    <div>entrer votre adresse mail</div>
    <br>
    <form method="post" action="php/action_reset.php">
      <input type="text" name="rmail"></input>
      <input type="submit" name="submit_r" value="envoyer">
    </form>
    <br>
    <?php
    if ($_GET['error'] == 1) {
      echo "<div style='color:red'>adresse mail non reconnu</div>";
    }
    ?>
  </main>
</body>

</html>
