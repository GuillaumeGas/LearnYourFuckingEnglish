<?php

$bdd = new PDO('mysql:host=localhost;dbname=english_voc', 'root', '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>