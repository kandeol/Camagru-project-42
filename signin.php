<?php

if (isset($_POST['submit']) && $_POST['submit'] == "Valider") {
    $isset = isset($_POST['user']) && isset($_POST['email']) && isset($_POST['pwd']) && isset($_POST['re_pwd']);
    $empty = !empty($_POST['user']) && !empty($_POST['email']) && !empty($_POST['pwd']) && !empty($_POST['re_pwd']);

    if ($isset && $empty) {

      $user = htmlspecialchars($_POST['user']);
      // $email = htmlspecialchars($POST['email']);
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          header('Location: inscription.php?error=4');
            exit();
        }
        try {
            $db = new PDO('mysql:host=mysql;port=3306;dbname=camagru', 'root', 'pass');
        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        if (strlen($user) < 5) {
          header('Location: inscription.php?error=3');
          exit();
        }
        if ($_POST['pwd'] != $_POST['re_pwd']) {
          header('Location: inscription.php?error=7');
          exit();
        }

        $longueurKey = 15;
        $key = "";
        $confirme = 0;
        for ($i=1;$i<$longueurKey;$i++) {
            $key .= mt_rand(0, 9);
        }

        $verify_user = $db->prepare('SELECT count(*) FROM login WHERE user = ?');
        $verify_email = $db->prepare('SELECT count(*) FROM login WHERE email = ?');

        $sql = $db->prepare('INSERT INTO login(user,pwd,email,confirmkey,confirme) VALUES(?, ?, ?, ?, ?)');
        $sql->bindParam(1, $user, PDO::PARAM_STR);
        $sql->bindParam(2, $_POST['pwd'], PDO::PARAM_STR);
        $sql->bindParam(3, $_POST['email'], PDO::PARAM_STR);
        $sql->bindParam(4, $key, PDO::PARAM_INT);
        $sql->bindParam(5, $confirme, PDO::PARAM_INT);

        $verify = $db->prepare('SELECT * FROM login WHERE user= ? AND pwd= ? AND email= ? AND confirmkey= ?');



        $verify_user->execute(array($user));
        $verify_email->execute(array($_POST['email']));
        $result_user = $verify_user->fetch();
        $result_email = $verify_email->fetch();
        if ($result_user[0] == 0 && $result_email[0] == 0) {
            //    $verify_user->closeCursor();
            //  $verify_email->closeCursor();
            // echo $_POST['user'];

            $sql->execute(array(
              $user,
              hash('whirlpool', $_POST['pwd']),
              $_POST['email'],
              $key,
              $confirme
            ));

//      $sql->closeCursor();

            $verify->execute(array(
              $user ,
              hash('whirlpool', $_POST['pwd']),
              $_POST['email'],
              $key
            ));

            $data = $verify->fetch();

            if ($verify->rowCount() == 1) {
                $header="MIME-Version: 1.0\r\n";
                $header.='From:"PrimFX.com"<support@primfx.com>'."\n";
                $header.='Content-Type:text/html; charset="uft-8"'."\n";
                $header.='Content-Transfer-Encoding: 8bit';
                $message='
                 <html>
                    <body>
                       <div align="center">
                          <a href="http://camagru/confirmation.php?pseudo='.urlencode($user).'&key='.$key.'">Confirmez votre compte !</a>
                       </div>
                    </body>
                 </html>
                     ';
                mail($_POST['email'], "Confirmation de compte", $message, $header);
                session_start();
                $_SESSION['id'] = $data['id_user'];
                $_SESSION['user'] = $user;
                $_SESSION['email'] = $_POST['email'];

                header('Location: membre.php');
                exit();
            } else {
                header('location: membre.php?error=5');
                exit();
            }
        } else {
            header('Location: inscription.php?error=2');
        }

        echo "CONNECT DB OK !";
        exit();
    } else {
        header('Location: inscription.php?error=1');
        exit();// code...
    }
} else {
    header('Location: inscription.php?error=6');
    exit();
}
