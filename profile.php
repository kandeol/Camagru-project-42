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
   <link type="text/css" rel="stylesheet" href="/css/membre.css" media="all"/>
 </head>
 <body>
   <header>
     <h2 class="welcome">Bienvenue
        <?php
          echo $_SESSION['user'];
        ?> !!</h2>
     <h1 class="title"> CAMAGRU </h1>
     <div class="deco"><a href="deconnexion.php"> se deconnecter </a></div>
     <div class="profile"><a href="profile.php"> Profile</a></div>
   </header>
 <!-- Menu de navigation du site -->
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
 </main>
 <div class="side">

 </div>
 <footer>

 </footer>

 </body>
 </html>
