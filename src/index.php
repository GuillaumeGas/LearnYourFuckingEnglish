<?php

/**
 * @author Guillaume Gas
 */

session_start();

require_once("mysql_connect.php"); //bdd var comes from here
require_once("libs/Smarty/libs/Smarty.class.php");

require_once("php/Message.class.php");
require_once("php/Home.class.php");
require_once("php/Connexion.class.php");
require_once("php/Content.class.php");
require_once("php/Profil.class.php");
//require_once("php/Voc.class.php");
//require_once("php/IrregularVerbs.class.php");

$smarty  = new Smarty();

if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "home";
}

//if he's not connected, we redirect the user to the connect page
if(!isset($_SESSION['user_connected']) || !$_SESSION['user_connected']) {
    $_SESSION['user_connected'] = false;
    $connexion = new Connexion($bdd, $smarty);
    $content_page   = $connexion->get_contenu();
    $content_header_menu = "";
} else {
    //we fetch the menu and header
    $content_header_menu = $smarty->fetch("html/header_menu.html");

    //we fetch the content page according to the requested page
    $content = new Content($bdd, $smarty, $page);
    $content_page = $content->get_content();
}

$smarty->assign("UserConnected", $_SESSION['user_connected']);
$smarty->assign("Content", $content_page);
$smarty->assign("HeaderMenu", $content_header_menu);

//affichage de la page
$smarty->display("html/index.html");

?>