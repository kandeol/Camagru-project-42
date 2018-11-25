<?php

session_start();


if (isset($_POST['submit_notif']) && !empty($_POST['submit_notif'])) {
    if (isset($_POST['notif']) && !empty($_POST['notif'])) {
        if ($_POST['notif'] == "on") {
            $notif = 1;
        } elseif ($_POST['notif'] == "off") {
            $notif = 0;
        }

        try {
            $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass87');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        $sql = $db->prepare('UPDATE login SET notif= ? WHERE id_user= ?');
        $sql->execute(array($notif, $_SESSION['id']));
        $sql->closeCursor();
        $_SESSION['notif'] = $notif;
        header('location: profile.php?s=1');
        exit();
    } else {
        header('location: profile.php?e=1');
    }
    // code...
} else {
    header('location: profile.php');
}
