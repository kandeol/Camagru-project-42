<?php

session_start();


$tmp_img = $_FILES['myfile']['tmp_name'];
$name_img = str_replace(' ', '', $_FILES['myfile']['name']);
$legalExtensions = array("jpg", "png", "jpeg", "gif");
$legalSize = "1000000";
$actualSize = $_FILES['myfile']['size'];
$extension = pathinfo($_FILES['myfile']['name'], PATHINFO_EXTENSION);


  $target_path = "images/";
  // echo $target_path.$name_img;
  $target_path = $target_path. "tmp.png";

  if (file_exists($target_path)) {
      unlink($target_path);
  }
  if (move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
      $_SESSION['path_img'] = $target_path;
  }
