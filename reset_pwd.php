<?php
 session_start();


 if (isset($_GET['key']) && !empty($_GET['key'])) {
     try {
         $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
     } catch (\Exception $e) {
         die('Erreur : ' . $e->getMessage());
     }

     $verify_key = $db->prepare('SELECT * FROM login WHERE confirmkey = ?');
     $verify_key->bindParam(1, $_GET['key'], PDO::PARAM_INT);
     $verify_key->execute(array($_GET['key']));
     $exist = $verify_key->rowCount();
     $result = $verify_key->fetch();

     if ($exist == 1) {
         if (isset($_POST['pwd']) && !empty($_POST['re_pwd'])) {
             if ($_POST['pwd'] == $_POST['re_pwd']) {
                 if (strlen($_POST['pwd']) < 5 || strlen($_POST['pwd']) > 20) {
                     $e_msg = "<span style='color:red'>le mot de passe doit faire entre 5 et 20 caracteres</span><br>";
                 } else {
                     $new_pwd_hash = hash('whirlpool', $_POST['pwd']);

                     $sql = $db->prepare('UPDATE login SET pwd= ? WHERE confirmkey = ?');
                     $sql->execute(array($new_pwd_hash, $_GET['key']));
                     $sql->closeCursor();

                     header('location: index.php?succes=1');
                     exit();
                 }
             } else {
                 $e_msg = "<span style='color:red'>les mots de passe ne sont identiques</span><br>";
             }
         }
         // code...
     } else {
         header('location: index.php');
         exit();
     }
     // code...
 } else {
     header('location: index.php');
     exit();
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
     } else {
         echo "<li><a href='index.php'>Connexion</a></li>";
     }
      ?>
    </ul>
  </div>
  <main>

    <div>Nouveau mot de passe</div><br>
    <form method="post" action="reset_pwd.php?key=<?= $_GET['key']?>">
      mot de passe :<br>
      <input type="password" name="pwd"><br>
      retaper le mot de passe :<br>
      <input type="password" name="re_pwd"><br>
      <input type="submit" name="submit" value="Valider">
    </form>

    <br>

    <br>
    <?php
    if ($_GET['error'] == 1) {
        echo "<div style='color:red'>adresse mail non reconnu</div>";
    }
     if (isset($e_msg)) {
         echo $e_msg;
     }
    ?>
  </main>
</body>

</html>
