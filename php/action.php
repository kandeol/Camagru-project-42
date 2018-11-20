<?php

session_start();

$db = new PDO('mysql:host=mysql;port=3306;dbname=camagru', 'root', 'pass');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$getid = (int) $_GET['id'];
$get_page = (int) $_GET['page'];


if (isset($_GET['id']) && !empty($_GET['id'])) {
  $check = $db->prepare('SELECT ID_IMG FROM image WHERE ID_IMG = ?');
  $check->execute(array($getid));

  if ($check->rowCount() == 1) {

      $check_likes = $db->prepare('SELECT ID_LIKES FROM T_LIKES WHERE ID_IMG = ? AND ID_USER = ?');
      $check_likes->execute(array($getid, $_SESSION['id']));

      if ($check_likes->rowCount() == 1) {
        $del = $db->prepare('DELETE FROM T_LIKES WHERE ID_IMG = ? AND ID_USER = ?');
        $del->execute(array($getid, $_SESSION['id']));
        # code...
      }
      else {
        $ins = $db->prepare('INSERT INTO T_LIKES (ID_IMG, ID_USER) VALUES(?, ?)');
        $ins->execute(array($getid,$_SESSION['id']));
      }
      $path_p_page = basename($_SERVER['HTTP_REFERER']);
      if (preg_match("/review/", $path_p_page)) {
            header('location: ../review.php?id_img='.$getid.'&page='.$get_page);
      }
      elseif (preg_match("/gallery/", $path_p_page)){
            header('location: ../gallery.php?page='.$get_page);
      }
  }
  else {
    exit('erreur fatale');
  }
}
 ?>
