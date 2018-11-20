<?php

session_start();
//var_dump($_FILES);
//var_dump($_POST);



//
// $response = array(
//   "path_file" => $_FILES['myfile']['tmp_name'],
//   "filter" => $_GET['filter']
// );
//
// echo json_encode($response);


$tmp_img = $_FILES['myfile']['tmp_name'];
$name_img = str_replace(' ','', $_FILES['myfile']['name']);
$legalExtensions = array("jpg", "png", "jpeg", "gif");
$legalSize = "1000000";
$actualSize = $_FILES['myfile']['size'];
$extension = pathinfo($_FILES['myfile']['name'], PATHINFO_EXTENSION);

// /*    echo $img."<br>";
// echo $actualSize."<br>";
// echo $extension."<br>";
// var_dump($legalExtensions);
// */
// if (!$tmp_img || $actualSize == 0) {
//   $error = true;
//   // code...
// }
//
//
// if (!$error) {
//   if ($actualSize < $legalSize) {
//     if (in_array($extension, $legalExtensions)) {
//
  $target_path = "images/";
  // echo $target_path.$name_img;
  $target_path = $target_path. "tmp.png";

  if (file_exists($target_path)) {
    unlink($target_path);
  }
  if (move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
    // echo "etape2";
    $_SESSION['path_img'] = $target_path;
  }
//
// $db = new PDO('mysql:host=mysql;port=3306;dbname=camagru', 'root', 'pass');
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// if ($_POST['type_filter'] && !empty($_POST['type_filter'])) {
//   echo "etape 3";
//   if ($_POST['type_filter'] == "filter1") {
//     $src = imagecreatefrompng("images/tail.png");
//   }
//   elseif ($_POST['type_filter'] == "filter2") {
//     $src = imagecreatefrompng("images/edward.png");
//   }
//   else {
//     $src = imagecreatefrompng("images/cadre.png");
//   }
//   if ($extension == "jpeg" || $extension == 'jpg') {
//   $dest = imagecreatefromjpeg($_SESSION['path_img']);
// }
// elseif ($extension == "png") {
//   $dest = imagecreatefrompng($_SESSION['path_img']);
// }else {
//   $dest = imagecreatefromgif($_SESSION['path_img']);
//
// }
//   $l_s = imagesx($src);
//   $h_s = imagesy($src);
//   $l_d = imagesx($dest);
//   $h_d = imagesy($dest);
// /*  echo imagesx($src);
//   echo "<br>";
//   echo imagesy($src);
//   echo "<br>";
//   echo imagesx($dest);
//   echo "<br>";
//   echo imagesy($dest);*/
//
//   $destination_x = $l_d - $l_s;
//   $destination_y =  $h_d - $h_s;
// //  imagecopy($dest, $src, $destination_x, $destination_y, 0, 0, $l_s, $h_s);
//   imagecopy($dest, $src, 0, 0, 0, 0, $l_s, $h_s);
//   $path_img = $dir . mktime() . ".jpg";
//   echo $path_img;
//   imagejpeg($dest, $path_img);
//   $target = $path_img;
//
  // $sql = $db->prepare('INSERT INTO image(PATH_IMG, ID_USER) VALUES(?, ?)');
  //   if ($sql->execute(array($target_path, $_SESSION['id'])) == TRUE) {
  //     echo "<br><img src=".$_target_path." height=439px width=550px /><br>";
  //   }
//     else {
//       echo "error";
//     }
// }
// echo "path : ".$target_path;
// unlink($target_path);
// }
// else {
// echo "Mauvais format d'images , only jpg/jpeg,png,gif";
// }
// echo "saut";
// }
// }
// else {
// echo "code error";
// }
