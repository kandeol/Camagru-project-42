<?php
session_start();

if (!isset($_SESSION['user']))
{
  header('location: index.php?error=nolog');
  exit();
}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
   <script type="text/javascript">
    function bascule(id)
    {
	      if (document.getElementById(id).style.visibility == "hidden")
			     document.getElementById(id).style.visibility = "visible";
	      else	document.getElementById(id).style.visibility = "hidden";
    }
   </script>
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
   <!--  <li><a class="active" href="#home">Home</a></li> -->
     <li><a href="profile.php">Profile</a></li>
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
   <div class="p_user"> Nom d'utilsateur : <?php echo $_SESSION['user'];?>  </div>
   <div class="bouton_modif" onclick="bascule('form_user');"> modifier </div>
     <div id="form_user">
       <form action="modif.php" method="post">
         <input type="text" name="new_user">
         <input type="submit" name="submit_modif" value="valider">
       </form>
     </div>
   <br>
   <div class="p_email"> Votre email : <?php echo $_SESSION['email']; ?></div>
   <div class="bouton_modif" onclick="bascule('form_email');"> modifier </div>
   <div id="form_email">
     <form action="modif.php" method="post">
       <input type="text" name="new_email">
       <input type="submit" name="submit_modif" value="valider">
     </form>
   </div>
   <div class="p_user"> modifier votre mot de passe </div>
   <div class="bouton_modif" onclick="bascule('form_pwd');"> modifier </div>
     <div id="form_pwd">
       <form action="modif.php" method="post">
         <div>Ancien mot de passe :</div>
         <input type="password" name="old_pwd"></input>
         <div>Nouveau mot de passe :</div>
         <input type="password" name="new_pwd">
         <input type="submit" name="submit_modif" value="valider">
       </form>
     </div>
   <br>
 </main>

 <footer>

 </footer>

 </body>
 </html>
