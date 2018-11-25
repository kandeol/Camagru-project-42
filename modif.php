<?php
session_start();


if (isset($_POST['submit_modif']) && $_POST['submit_modif'] == "valider") {
    if (isset($_POST['new_user']) && !empty($_POST['new_user'])) {
        try {
            $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        $new_user = htmlspecialchars($_POST['new_user']);

        $verify_user = $db->prepare('SELECT count(*) FROM login WHERE user = ?');
        $verify_user->bindParam(1, $new_user, PDO::PARAM_STR);
        $verify_user->execute(array($new_user));
        $result = $verify_user->fetch();

        if ($result[0] == 0) {
            $sql = $db->prepare('UPDATE login SET user= ? WHERE id_user= ?');
            $sql->bindParam(1, $new_user, PDO::PARAM_STR);
            $sql->bindParam(2, $_SESSION['id'], PDO::PARAM_INT);
            $sql->execute(array($new_user, $_SESSION['id']));
            $sql->closeCursor();

            echo " records UPDATED successfully";
            $_SESSION['user'] = $new_user;
            header('location: profile.php?error=succes1');
            exit();
        } else {
            header('location: profile.php?error=error_user');
            exit();
        }
    }

    if (isset($_POST['new_email']) && !empty($_POST['new_email'])) {
        if (!filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL)) {
            echo "erreur format adresse mail";
            header('location: profile.php?error=modif_mail');
            exit();
        }
        try {
            $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }


        $verify_email = $db->prepare('SELECT count(*) FROM login WHERE email = ?');
        $verify_email->bindParam(1, $new_user, PDO::PARAM_STR);
        $verify_email->execute(array($_POST['new_email']));
        $result = $verify_email->fetch();

        if ($result[0] == 0) {
            $sql = $db->prepare('UPDATE login SET email= ? WHERE id_user= ?');
            $sql->bindParam(1, $_POST['new_email'], PDO::PARAM_STR);
            $sql->bindParam(2, $_SESSION['id'], PDO::PARAM_INT);
            $sql->execute(array($_POST['new_email'], $_SESSION['id']));
            $sql->closeCursor();

            $_SESSION['email'] = $_POST['new_email'];
            header('location: profile.php?error=succes1');
            exit();
        } else {
            header('location: profile.php?error=error_email');
            exit();
        }
    }

    if (isset($_POST['old_pwd'], $_POST['new_pwd']) && !empty($_POST['old_pwd']) && !empty($_POST['new_pwd'])) {
        try {
            $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $verify_pwd = $db->prepare('SELECT pwd FROM login WHERE id_user = :id_user');
        $verify_pwd->bindParam(':id_user', $_SESSION['id'], PDO::PARAM_INT);
        $verify_pwd->execute(array(':id_user' => $_SESSION['id']));
        $result = $verify_pwd->fetch();

        $old_pwd_hash = hash('whirlpool', $_POST['old_pwd']);

        if ($old_pwd_hash == $result['pwd']) {
            if ($_POST['old_pwd'] == $_POST['new_pwd']) {
                header('location: profile.php?error=errornewpwd');
                exit();
            }
            if (strlen($_POST['new_pwd']) < 5 || strlen($_POST['new_pwd']) > 20) {
                header('location: profile.php?error=errorpwd');
                exit();
            }
            $new_pwd_hash = hash('whirlpool', $_POST['new_pwd']);

            $sql = $db->prepare('UPDATE login SET pwd= ? WHERE id_user= ?');
            $sql->execute(array($new_pwd_hash, $_SESSION['id']));
            $sql->closeCursor();
        } else {
            header('location: profile.php?error=errorpwd');
            exit();
        }

        header('location: profile.php?error=succes3');
        exit();
    } else {
        header('location: profile.php?error=errorpwd');
        exit();
    }
} else {
    header('location: membre.php?error=submit');
    exit();
}
