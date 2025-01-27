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
            $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        if (strlen($user) < 5 || strlen($user) > 20) {
            header('Location: inscription.php?error=3');
            exit();
        }

        if ($_POST['pwd'] != $_POST['re_pwd']) {
            header('Location: inscription.php?error=7');
            exit();
        }

        if (strlen($_POST['pwd']) < 5 || strlen($_POST['pwd']) > 20) {
            header('Location: inscription.php?error=8');
            exit();
        }

        if (!preg_match('/[A-Z]/', $_POST['pwd']) || !preg_match('/[a-z]/', $_POST['pwd']) || !preg_match('/[0-9]/', $_POST['pwd'])) {
          header('Location: inscription.php?error=9');
          exit();
        }
        $longueurKey = 15;
        $key = "";
        $confirme = 0;
        $notif = 1;
        for ($i=1;$i<$longueurKey;$i++) {
            $key .= mt_rand(0, 9);
        }

        $verify_user = $db->prepare('SELECT count(*) FROM login WHERE user = ?');
        $verify_user->bindParam(1, $user, PDO::PARAM_STR);
        $verify_email = $db->prepare('SELECT count(*) FROM login WHERE email = ?');
        $verify_email->bindParam(1, $_POST['email'], PDO::PARAM_STR);

        $sql = $db->prepare('INSERT INTO login(user,pwd,email,confirmkey,confirme,notif) VALUES(?, ?, ?, ?, ?, ?)');
        $sql->bindParam(1, $user, PDO::PARAM_STR);
        $sql->bindParam(2, $_POST['pwd'], PDO::PARAM_STR);
        $sql->bindParam(3, $_POST['email'], PDO::PARAM_STR);
        $sql->bindParam(4, $key, PDO::PARAM_INT);
        $sql->bindParam(5, $confirme, PDO::PARAM_INT);
        $sql->bindParam(6, $notif, PDO::PARAM_INT);

        $verify = $db->prepare('SELECT * FROM login WHERE user= ? AND pwd= ? AND email= ? AND confirmkey= ?');



        $verify_user->execute(array($user));
        $verify_email->execute(array($_POST['email']));
        $result_user = $verify_user->fetch();
        $result_email = $verify_email->fetch();
        if ($result_user[0] == 0 && $result_email[0] == 0) {
            $sql->execute(array(
              $user,
              hash('whirlpool', $_POST['pwd']),
              $_POST['email'],
              $key,
              $confirme,
              $notif
            ));

            $verify->execute(array(
              $user ,
              hash('whirlpool', $_POST['pwd']),
              $_POST['email'],
              $key
            ));

            $data = $verify->fetch();

            if ($verify->rowCount() == 1) {
                $header="MIME-Version: 1.0\r\n";
                $header.='From:"camagru.com"<Kalys21@gmail.com>'."\n";
                $header.='Content-Type:text/html; charset="uft-8"'."\n";
                $header.='Content-Transfer-Encoding: 8bit';
                $message='
                 <html>
                    <body>
                       <div align="center">
                          <a href="http://127.0.0.1:8080/confirmation.php?user='.urlencode($user).'&key='.$key.'">Confirmez votre compte !</a>
                       </div>
                    </body>
                 </html>
                     ';
                mail($_POST['email'], "Confirmation de compte", $message, $header);
                session_start();
                $_SESSION['id'] = $data['id_user'];
                $_SESSION['notif'] = $notif;
                $_SESSION['email'] = $_POST['email'];

                header('Location: index.php?succes=2');
                exit();
            } else {
                header('location: membre.php?error=5');
                exit();
            }
        } else {
            header('Location: inscription.php?error=2');
            exit();
        }

        echo "CONNECT DB OK !";
        exit();
    } else {
        header('Location: inscription.php?error=1');
        exit();
    }
} else {
    header('Location: inscription.php?error=6');
    exit();
}
