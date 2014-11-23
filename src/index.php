<?php

/**
 * @author Guillaume Gas
 */

session_start();

//session_destroy();
//unset($_SESSION['user_connected']);

require_once("mysql_connect.php"); //bdd var comes from here
require_once("libs/Smarty/libs/Smarty.class.php");

require_once("php/Content.class.php");

$smarty  = new Smarty();

if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "home";
}

//we fetch the content page according to the requested page
$content = new Content($bdd, $smarty, $page);
$content_page = $content->get_content();

$smarty->assign("Page", $page);
$smarty->assign("Content", $content_page);

header('Content-Type: text/html; charset=utf-8');
//affichage de la page
$smarty->display("html/index.html");

?>