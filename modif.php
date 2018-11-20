<?php
session_start();


if (isset($_POST['submit_modif']) && $_POST['submit_modif'] == "valider")
{

    if (isset($_POST['new_user']) && !empty($_POST['new_user']))
    {
      try
      {
        try
        {
          $db = new PDO('mysql:host=mysql;port=3306;dbname=camagru', 'root', 'pass');
        }
        catch (\Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = $db->prepare('UPDATE login SET user= ? WHERE id= ?');
        $sql->execute(array($_POST['new_user'], $_SESSION['id']));
        $sql->closeCursor();

        echo " records UPDATED successfully";
        $_SESSION['user'] = $_POST['new_user'];
        header('location: membre.php?error=succes');
      }
      catch(PDOException $e)
      {
        echo $sql . "<br>" . $e->getMessage();
      }
    }
    else
    {
      header('location: membre.php?error=new_user');
    }

    if (isset($_POST['new_email']) && !empty($_POST['new_email']))
    {
      if (!filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL))
      {
        echo "erreur format adresse mail";
        header('location: profile.php?error=modif_mail');
        exit();
      }

      try
      {
  /*      try
        {
          $db = new PDO('mysql:host=localhost;port=3306;dbname=camagru', 'root', 'pass');
        }
        catch (\Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
*/
        $sql = $db->prepare('UPDATE login SET email= ? WHERE id= ?');
        $sql->execute(array($_POST['new_email'], $_SESSION['id']));
        $sql->closeCursor();

        $_SESSION['email'] = $_POST['new_email'];
      }
      catch(PDOException $e)
      {
        echo $sql . "<br>" . $e->getMessage();
      }
    }
}
else
{
  header('location: membre.php?error=submit');
}



 ?>
