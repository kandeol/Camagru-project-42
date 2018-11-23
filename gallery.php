<?php
session_start();

$db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$imagesParPage = 6;
$imagesTotalesReq = $db->prepare('SELECT ID_IMG FROM image');
$imagesTotalesReq->execute();
$imagesTotales = $imagesTotalesReq->rowCount();
$pagesTotales = ceil($imagesTotales/$imagesParPage);

if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pagesTotales) {
  $_GET['page'] = intval($_GET['page']);
  $pageCourante = $_GET['page'];
}
else {
  $pageCourante = 1;
}

$depart = ($pageCourante-1)*$imagesParPage;


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
<!-- Menu de navigation du site -->
<main id="main_gallery">
  <h2>Gallery</h2>
  <br><br><br>
  <?php
  $images = $db->prepare('SELECT * FROM image ORDER BY DATE_IMG LIMIT '.$depart.','.$imagesParPage);
  $images->execute();
  while ($result = $images->fetch()) {

    $likes = $db->prepare('SELECT ID_LIKES FROM T_LIKES WHERE ID_IMG = ?');
    $likes->execute(array($result['ID_IMG']));
    $likes = $likes->rowCount();

      echo "<div id='div_gallery'><a href='review.php?id_img=".$result['ID_IMG']."&page=".$pageCourante."' ><img id='img_save' src=".$result['PATH_IMG']." height=240px width=320px /></a><br>  ";
      if ($_SESSION['user']) {
       echo "<a class='like_gal' href='php/action.php?id=".$result['ID_IMG']."&page=".$pageCourante."'>J'aime</a> (".$likes.")</div>";
      }
      else {
        echo "<a class='like_gal'>J'aime</a> (".$likes.")</div>";
      }
  }
   ?>
   <br><br>
   <div class="pagination">
   <?php
   for($i=1;$i<=$pagesTotales;$i++){
     if ($i == $pageCourante) {
       echo '<a class="active" href="gallery.php?page='.$i.'">'.$i.' </a>';
     }
     else {
     echo '<a href="gallery.php?page='.$i.'">'.$i.' </a>';


      }
     }
    ?>
  </div>
</main>
</body>
</html>
