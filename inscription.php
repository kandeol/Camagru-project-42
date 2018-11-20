<?php
session_start();

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
    <h2>INSCRIPTION</h2>
    <form method="post" action="signin.php">
      nom d'utilisateur :<br>
      <input type="text" name="user"><br>
     adresse mail :<br>
      <input type="text" name="email"><br>
     mot de passe :<br>
      <input type="password" name="pwd"><br>
     retaper le mot de passe :<br>
      <input type="password" name="re_pwd"><br>
      <input type="submit" name="submit" value="Valider">
   </form>
      <?php
    if ($_GET['error'] == 1) {
        echo "<div style='color:red'>les champs ne sont pas tous remplies</div>";
    } elseif ($_GET['error'] == 2) {
        echo "<div style='color:red'>nom d'utilisateur ou adresse mail deja enregistrer</div>";
    } elseif ($_GET['error'] == 3) {
        echo "<div style='color:red'>nom d'utilsateur doit faire au moins 5 caracteres</div>";
    # code...
    } elseif ($_GET['error'] == 4) {
        echo "<div style='color:red'>adresse mail non valide </div>";
    # code...
    } elseif ($_GET['error'] == 5) {
        echo "<div style='color:red'>Erreur ecriture de la base de donnee</div>";
    # code...
    } elseif ($_GET['error'] == 6) {
        echo "<div style='color:red'>Formulaire non Valider</div>";
    # code...
    } elseif ($_GET['error'] == 7) {
        echo "<div style='color:red'>les deux champs du mot de passe ne sont pas identiques</div>";
        # code...
    }

    ?>
  </main>

</body>

</html>
