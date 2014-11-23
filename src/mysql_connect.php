<?php

$bdd = new PDO('mysql:host=localhost;dbname=english_voc', 'root', '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/*$bdd->query("ALTER DATABASE english_voc CHARACTER SET UTF8");
$bdd->query("ALTER TABLE voc CHARACTER SET UTF8");
$bdd->query("ALTER TABLE voc_already_read CHARACTER SET UTF8");
$bdd->query("ALTER TABLE voc CONVERT TO CHARACTER SET UTF8");
$bdd->query("ALTER TABLE voc_already_read CONVERT TO CHARACTER SET UTF8");*/
$bdd->query("SET NAMES UTF8");

?>