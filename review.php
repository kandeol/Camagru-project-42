<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('location: index.php');
}

$db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id_img']) && !empty($_GET['id_img'])) {
    $get_id_img = htmlspecialchars($_GET['id_img']);
    $get_p_page = $_GET['page'];

    $likes = $db->prepare('SELECT ID_LIKES FROM T_LIKES WHERE ID_IMG = ?');
    $likes->bindParam(1, $get_id_img, PDO::PARAM_INT);
    $likes->execute(array($get_id_img));
    $likes = $likes->rowCount();



    if (isset($_POST['submit_comment'])) {
        if (isset($_POST['comment']) && !empty($_POST['comment'])) {
            $comment = htmlspecialchars($_POST['comment']);

            $ins = $db->prepare('INSERT INTO T_COM (USER, TEXT_COM, ID_IMG) VALUES(?, ?, ?)');
            $ins->bindParam(1, $_SESSION['user'], PDO::PARAM_STR);
            $ins->bindParam(2, $comment, PDO::PARAM_STR);
            $ins->bindParam(3, $get_id_img, PDO::PARAM_INT);
            $ins->execute(array($_SESSION['user'], $comment, $get_id_img));
            $c_msg = "<span style='color:green'>Votre commentaire a bien ete ajoute</span>";

            $sql = $db->prepare('SELECT ID_USER FROM image WHERE ID_IMG= ?');
            $sql->execute(array($get_id_img));
            $result = $sql->fetch();
            $sql->closeCursor();

            if ($result['ID_USER'] != $_SESSION[id]) {


                  $sql = $db->prepare('SELECT email FROM login WHERE id_user= ?');
                  $sql->execute(array($result['ID_USER']));
                  $result = $sql->fetch();


                  $header="MIME-Version: 1.0\r\n";
                  $header.='From:"camagru.com"<Kalys21@gmail.com>'."\n";
                  $header.='Content-Type:text/html; charset="uft-8"'."\n";
                  $header.='Content-Transfer-Encoding: 8bit';
                  $message='
                   <html>
                      <body>
                         <div align="center">
                            <h3>Vous avez recu un nouveau commentaire sur vos photos ! </h3>
                            <br>
                            <span>"'.$comment.'"</span>
                         </div>
                      </body>
                   </html>
                       ';
                  mail($result['email'], "Nouveau commentaire", $message, $header);
          }
        } else {
            $c_msg = "Erreur : Tous les champs doivent etre completes";
        }
        # code...
    }
} else {
    header('location: gallery.php?page=1');
}

$comments = $db->prepare('SELECT * FROM T_COM WHERE ID_IMG = ? ORDER BY ID_COM DESC');
$comments->execute(array($get_id_img));

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
  <main id="main_review">
    <div id="return_gallery"><a href="gallery.php?page=<?= $get_p_page?>">Retour sur la gallery</a></div>
    <h2>REVIEW</h2>
    <br><br>
    <?php

  $id = $_GET['id_img'];
  $sql = $db->prepare('SELECT PATH_IMG FROM image WHERE ID_IMG = ?');
  $sql->bindParam(1, $_GET['id_img'], PDO::PARAM_INT);
  $sql->execute(array($_GET['id_img']));
  $path_img = $sql->fetch();

  echo "<img src='".$path_img['PATH_IMG']."' height=240px width=320px />";

   ?>
    <br><br>
    <a href="php/action.php?id=<?= $id?>&page=<?= $get_p_page?>">J'aime</a> (<?= $likes ?>)
    <br><br>
    <h2>Commentaires</h2>
    <form method="post">
      <textarea name="comment" placeholder="Votre commentaire..."></textarea><br>
      <input type="submit" value="poster votre commetaire" name="submit_comment"/>
   </form>
      <?php if (isset($c_msg)) {
              echo $c_msg;
            }
      ?>
      <br>
      <div id="con_com">
        <?php  while ($c = $comments->fetch()) {
                  echo "<div class='div_com'>".$c['USER']." : ".$c['TEXT_COM']."</div><br>";
                }
        ?>
      </div>
  </main>
</body>

</html>
