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
 <main id="main_mem">

<video id="video" width="320" height="240" autoplay></video>
<button id="snap">Prendre une photo</button>
<canvas id="canvas"></canvas>
<!-- <img src="" id="photo" alt="photo"> -->
<script src="js/script_webcam.js"></script>
<?php
// var_dump($_FILES);
// var_dump($_POST);
 // echo "path_decode :".$_FILES['myfile']['tmp_name'];
$img = str_replace(' ','', $_FILES['myimage']['name']);
$target_path = "images/";
$target_path = $target_path.basename($img);

echo $_FILES['photo']['tmp_name'];
if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {

  $_SESSION['path_img'] = $target_path;
  echo $target_path;
}
else {
  // echo "error";
}
 ?>
<h3>OR</h3>
<!--<form method="POST" enctype="multipart/form-data">
 <input type="file" name="myimage">
 <input type="submit" name="submit_image" value="Upload">
</form>-->
<br>
<br>
<?php
/*
if (isset($_POST['submit_image']))
{
  $img = str_replace(' ','', $_FILES['myimage']['name']);
  $target_path = "images/";
  echo $target_path.$img;
  $target_path = $target_path.basename($img);
//if (file_exists($target_path)) {
//  echo "Fichier déjà uploader";
//  }
//else {
  if (move_uploaded_file($_FILES['myimage']['tmp_name'], $target_path)) {

    $_SESSION['path_img'] = $target_path;
      echo $target_path;
      echo "<br><img src=".$_FILES." height=200 width=300 /><br>";
  //    unlink($_target_path);
//    }
  }
}*/
?>
<br>
<br>
  <form action="membre.php" method="post" enctype="multipart/form-data">
    <input type="file" name="myimage">
    <input type="radio" name="type_filter" value="filter1" onclick="bascule('bmount')" checked><img src="data/tail.png" height=50 width="80" />
    <input type="radio" name="type_filter" value="filter2" onclick="bascule('bmount')" ><img src="data/edward.png" height=50 width="80" />
    <input type="radio" name="type_filter" value="filter3" onclick="bascule('bmount')" ><img src="data/cadre.png" height=50 width="80" />
    <br>
    <input id="bmount" type="submit" name="submit_filter" value="Montage" />
  </form>
  <script type="text/javascript" src="js/fusion_button.js"></script>
<?php

  $dir = "save/";
  if ($_POST['submit_filter'] && $_POST['submit_filter'] == "Montage") {

    $tmp_img = $_FILES['myimage']['tmp_name'];
    $name_img = str_replace(' ','', $_FILES['myimage']['name']);
    $legalExtensions = array("jpg", "png", "jpeg", "gif");
    $legalSize = "1000000";
    $actualSize = $_FILES['myimage']['size'];
    $extension = pathinfo($_FILES['myimage']['name'], PATHINFO_EXTENSION);

/*    echo $img."<br>";
    echo $actualSize."<br>";
    echo $extension."<br>";
    var_dump($legalExtensions);
*/
    if (!$tmp_img || $actualSize == 0) {
      if (file_exists("images/tmp.png")) {
        $error = false;
        $name_img = "tmp.png";
        $extension = "png";
        $legalSize = "15000";
        // echo "test1";
      }else {
      $error = true;
      echo $tmp_img = $_FILES['myimage']['tmp_name'];
      echo "test0";
      }
    }


    if (!$error) {
      if ($actualSize < $legalSize) {
        if (in_array($extension, $legalExtensions)) {

      $target_path = "images/";
      echo $target_path.$name_img;
      $target_path = $target_path.basename($name_img);
      // if (move_uploaded_file($_FILES['myimage']['tmp_name'], $target_path)) {
         // echo "etape2";
        // $_SESSION['path_img'] = "images/tmp.jpg";
        $_SESSION['path_img'] = $target_path;
      // }

    $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_POST['type_filter'] && !empty($_POST['type_filter'])) {
      // echo "etape 3";
      if ($_POST['type_filter'] == "filter1") {
        $src = imagecreatefrompng("images/tail.png");
      }
      elseif ($_POST['type_filter'] == "filter2") {
        $src = imagecreatefrompng("images/edward.png");
      }
      else {
        $src = imagecreatefrompng("images/cadre.png");
      }
      if ($extension == "jpeg" || $extension == 'jpg') {
      $dest = imagecreatefromjpeg($_SESSION['path_img']);
    }
    elseif ($extension == "png") {
      $dest = imagecreatefrompng($_SESSION['path_img']);
    }else {
      $dest = imagecreatefromgif($_SESSION['path_img']);

    }
      $l_s = imagesx($src);
      $h_s = imagesy($src);
      $l_d = imagesx($dest);
      $h_d = imagesy($dest);
    /*  echo imagesx($src);
      echo "<br>";
      echo imagesy($src);
      echo "<br>";
      echo imagesx($dest);
      echo "<br>";
      echo imagesy($dest);*/

      $destination_x = $l_d - $l_s;
      $destination_y =  $h_d - $h_s;
    //  imagecopy($dest, $src, $destination_x, $destination_y, 0, 0, $l_s, $h_s);
      imagecopy($dest, $src, 0, 0, 0, 0, $l_s, $h_s);
      $path_img = $dir . mktime() . ".jpg";
      echo $path_img;
      imagejpeg($dest, $path_img);
      $target = $path_img;

      $sql = $db->prepare('INSERT INTO image(PATH_IMG, ID_USER) VALUES(?, ?)');
        if ($sql->execute(array($target, $_SESSION['id'])) == TRUE) {
          echo "<br><img src=".$target." height=240px width=320px /><br>";
        }
        else {
          echo "error";
        }
  }
  // echo "path : ".$target_path;
  unlink($target_path);
  }
  else {
    echo "Mauvais format d'images , only jpg/jpeg,png,gif";
  }
  // echo "saut";
}
}
else {
  echo "code error";
}
}  else {
    echo "Pas d'upload !";
  }
 ?>
</main>
<div class="side">
<?php
$db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = $db->prepare('SELECT ID_IMG, PATH_IMG FROM image WHERE ID_USER = ? ORDER BY DATE_IMG DESC');
$sql->execute(array($_SESSION['id']));
while ($result = $sql->fetch()) {
    echo "<br><img id='img_save' src=".$result['PATH_IMG']." height=240px width=320px /><br>";
    echo "<div><a href='delete_image.php?id_i=".$result['ID_IMG']."' class='del_img'>Supprimer</a></div>";
}
?>


</div>
<footer>

</footer>

</body>
</html>
