<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('location: index.php?error=nolog');
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
  <div id="bar_nav">
    <ul>
      <li><a href="profile.php">Profile</a></li>
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
  <main id="main_mem">
    <video id="video" width="320" height="240" autoplay></video>
    <button id="snap">Prendre une photo</button>
    <canvas id="canvas"></canvas>
    <script src="js/script_webcam.js"></script>

    <h3>OR</h3>
    <br>
    <br>
    <?php

?>
    <br>
    <br>
    <form action="membre.php" method="post" enctype="multipart/form-data">
      <input type="file" name="myimage"><br>
      <input class="filter" type="radio" name="type_filter" value="filter1" checked><img src="images/tail.png" height=50 width="80" />
      <input class="filter" type="radio" name="type_filter" value="filter2"><img src="images/edward.png" height=50 width="80" />
      <input class="filter" type="radio" name="type_filter" value="filter3"><img src="images/cadre.png" height=50 width="80" />
      <!-- <input type="radio" name="type_filter" value="filter4"><img src="images/filtro.png" height=50 width="80" />
      <input type="radio" name="type_filter" value="filter5"><img src="images/light_texture_ix_by_avenue_of_art.png" height=50 width="80" /> -->
      <br>
      <input type="submit" name="submit_filter" value="Montage" />
    </form>
    <script type="text/javascript" src="js/fusion_button.js"></script>
    <?php

  $dir = "save/";
  if ($_POST['submit_filter'] && $_POST['submit_filter'] == "Montage") {
      if (!is_dir($dir)) {
          mkdir($dir);
      }
      $tmp_img = $_FILES['myimage']['tmp_name'];
      $name_img = str_replace(' ', '', $_FILES['myimage']['name']);
      $legalExtensions = array("jpg", "png", "jpeg");
      $legalSize = "1000000";
      $actualSize = $_FILES['myimage']['size'];
      $extension = pathinfo($_FILES['myimage']['name'], PATHINFO_EXTENSION);


      if (!$tmp_img || $actualSize == 0) {
          if (file_exists("images/tmp.png")) {
              $error = false;
              $name_img = "tmp.png";
              $extension = "png";
              $legalSize = "15000";
          // echo "test1";
          } else {
              $error = true;
              // echo $tmp_img = $_FILES['myimage']['tmp_name'];
              // echo "test0";
          }
      }


      if (!$error) {
          if ($actualSize < $legalSize) {
              if (in_array($extension, $legalExtensions)) {
                  $target_path = "images/";
                  // echo $target_path.$name_img;
                  $target_path = $target_path.basename($name_img);
                  if (isset($_FILES['myimage']['tmp_name']) && !empty($_FILES['myimage']['tmp_name'])) {
                      if (move_uploaded_file($_FILES['myimage']['tmp_name'], $target_path)) {
                          // echo "etape2";
                          // $_SESSION['path_img'] = "images/tmp.jpg";
                          $_SESSION['path_img'] = $target_path;
                      }
                  }


                  if ($_POST['type_filter'] && !empty($_POST['type_filter'])) {
                      // echo "etape 3";
                      if ($_POST['type_filter'] == "filter1") {
                          $src = imagecreatefrompng("images/tail.png");
                      } elseif ($_POST['type_filter'] == "filter2") {
                          $src = imagecreatefrompng("images/edward.png");
                      } elseif ($_POST['type_filter'] == "filter3") {
                          $src = imagecreatefrompng("images/cadre.png");
                      } elseif ($_POST['type_filter'] == "filter4") {
                          $src = imagecreatefrompng("images/filtro.png");
                      } elseif ($_POST['type_filter'] == "filter5") {
                          $src = imagecreatefrompng("images/light_texture_ix_by_avenue_of_art.png");
                      }

                      if ($extension == "jpeg" || $extension == 'jpg') {
                          $dest = imagecreatefromjpeg($_SESSION['path_img']);
                      } elseif ($extension == "png") {
                          $dest = imagecreatefrompng($_SESSION['path_img']);
                      }

                      $l_s = imagesx($src);
                      $h_s = imagesy($src);
                      $l_d = imagesx($dest);
                      $h_d = imagesy($dest);

                      $destination_x = $l_d - $l_s;
                      $destination_y =  $h_d - $h_s;
                      //  imagecopy($dest, $src, $destination_x, $destination_y, 0, 0, $l_s, $h_s);
                      //	imagecopy($dest, $src, 0, 0, 0, 0, $l_s, $h_s);

                      imagecopyresampled($dest, $src, 0, 0, 0, 0, $l_d, $h_d, $l_s, $h_s);

                      $path_img = $dir . mktime() . ".jpg";
                      // echo $path_img;
                      imagejpeg($dest, $path_img);
                      $target = $path_img;

                      $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
                      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                      $sql = $db->prepare('INSERT INTO image(PATH_IMG, ID_USER) VALUES(?, ?)');
                      $sql->bindParam(1, $target, PDO::PARAM_STR);
                      $sql->bindParam(2, $_SESSION['id'], PDO::PARAM_INT);

                      if ($sql->execute(array($target, $_SESSION['id'])) == true) {
                          echo "<br><span style='color:green'>Apercu</span><br>";
                          echo "<br><img id='apercu' src=".$target." height=240px width=320px /><br>";
                      } else {
                          echo "error";
                      }
                  }
                  // echo "path : ".$target_path;
                  unlink($target_path);
              } else {
                  $c_msg = "Mauvais format d'images , only jpg/jpeg,png";
              }
              // echo "saut";
          }else {
            $c_msg = "Image trop lourde ";
          }
      } else {
          echo "<div style='color:red'>Pas de photo ni d'upload</div>";
      }
  } else {
      echo "Pas d'upload !";
  }

 ?>

    <?php if (isset($c_msg)) {
     echo $c_msg;
 }
   ?>
  </main>
  <div class="side">
    <?php
$db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = $db->prepare('SELECT ID_IMG, PATH_IMG FROM image WHERE ID_USER = ? ORDER BY DATE_IMG DESC');
$sql->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
$sql->execute(array($_SESSION['id']));

while ($result = $sql->fetch()) {
    echo "<br><img id='img_save' src=".$result['PATH_IMG']." height=240px width=320px /><br>";
    echo "<div><a href='delete_image.php?id_i=".$result['ID_IMG']."' class='del_img'>Supprimer</a></div>";
}
?>


  </div>
  <footer>
    CAMAGRU 2018
  </footer>

</body>

</html>
