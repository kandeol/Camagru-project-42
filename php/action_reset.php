<?php

session_start();

if (isset($_POST['submit_r']) && !empty($_POST['submit_r'])) {

    if (isset($_POST['rmail']) && !empty($_POST['rmail'])) {

      try {
          $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
      } catch (\Exception $e) {
          die('Erreur : ' . $e->getMessage());
      }

      $verify_email = $db->prepare('SELECT * FROM login WHERE email = ?');
      $verify_email->bindParam(1, $_POST['rmail'], PDO::PARAM_STR);
      $verify_email->execute(array($_POST['rmail']));
      $exist = $verify_email->rowCount();
      $result = $verify_email->fetch();



      $longueurKey = 15;
      $key_reset = "";
      for ($i=1;$i<$longueurKey;$i++) {
          $key_reset .= mt_rand(0, 9);
      }

      if ($exist == 1) {

        $sql = $db->prepare('UPDATE login SET confirmkey= ? WHERE email= ?');
        $sql->execute(array($key_reset, $_POST['rmail']));
        $header="MIME-Version: 1.0\r\n";
        $header.='From:"camagru.com"<andeol.kevin@gmail.com>'."\n";
        $header.='Content-Type:text/html; charset="uft-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
        $message='
         <html>
            <body>
               <div align="center">
                  <a href="http://127.0.0.1:8080/reset_pwd.php?key='.$key_reset.'">Lien pour reinitialiser le mot de passe !</a>
               </div>
            </body>
         </html>
             ';
        mail($_POST['rmail'], "Confirmation de compte", $message, $header);
        header('location: ../index.php');

      }else {
        header('location: ../reset.php?error=1');
      }
    }
}

 ?>
