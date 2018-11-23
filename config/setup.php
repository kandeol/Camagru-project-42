<?php

require 'database.php';


function setup($db, $db_name)
{
  $sql = "DROP DATABASE IF EXISTS ".$db_name;
  $result = $db->exec($sql);

  $sql = "CREATE DATABASE IF NOT EXISTS ".$db_name;
	$result = $db->exec($sql);


//  $sql = $db->prepare('CREATE DATABASE '.$db_name);
  $sql = "USE ".$db_name;
  $result = $db->exec($sql);

  $sql = "CREATE TABLE `login` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `confirmkey` varchar(15) NOT NULL,
  `confirme` int(1) NOT NULL
)";
  $result = $db->exec($sql);

  $sql = "CREATE TABLE image (
    ID_IMG int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    PATH_IMG varchar(255) NOT NULL,
    ID_USER int(11) NOT NULL,
    DATE_IMG DATETIME DEFAULT NOW(),
    FOREIGN KEY (ID_USER) REFERENCES login(id_user)
  )";
  $result = $db->exec($sql);

  $sql = "CREATE TABLE T_LIKES (
    ID_LIKES int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ID_IMG int(11) NOT NULL,
    ID_USER int(11) NOT NULL,
    FOREIGN KEY (ID_IMG) REFERENCES image(ID_IMG),
    FOREIGN KEY (ID_USER) REFERENCES login(id_user)
  )";
  $result = $db->exec($sql);

  $sql = "CREATE TABLE T_COM (
    ID_COM int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    USER varchar(255) NOT NULL,
    TEXT_COM text NOT NULL,
    ID_IMG int(11) NOT NULL,
    DATE_COM DATETIME DEFAULT NOW(),
    FOREIGN KEY (ID_IMG) REFERENCES image(ID_IMG)
    -- FOREIGN KEY (USER) REFERENCES login(user)
  )";
  $result = $db->exec($sql);
}

$dsn = "mysql:host=".$DB_HOST;
$db = new PDO($dsn,$DB_USER,$DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
setup($db, $DB_NAME);
echo "setup completed".PHP_EOL;


 ?>
