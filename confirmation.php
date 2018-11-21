<?php

session_start();

try {
    $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
} catch (\Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (isset($_GET['user'] , $_GET['key']) && !empty($_GET['user']) && !empty($_GET['key'])) {
  $user = htmlspecialchars(urldecode($_GET['user']));
  $key = htmlspecialchars($_GET['key']);

  $sql =$db->prepare('SELECT * FROM login WHERE user = ?');
  $sql->execute(array($user));
  $userexist = $sql->rowCount();

  if ($userexist == 1) {
    $result = $sql->fetch();
    if ($result['confirme'] == 0) {
      $update = $db->prepare('UPDATE login SET confirme = 1 WHERE user= ? AND confirmkey= ?');
      $update->execute(array($user, $key));
      if ($update->rowCount() == 1) {
      $_SESSION['user'] == $result['user'];
      header('location: welcome.php?sit=1');
    }else {
      header('location: welcome.php?sit=3');
    }
    }else {
      header('location: welcome.php?sit=2');
    }
    // code...
  }else {
    header('location: welcome.php?sit=3');
  }

}




 ?>
