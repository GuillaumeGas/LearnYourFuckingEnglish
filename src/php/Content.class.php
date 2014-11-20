<?php

/*
 fetch the content page according to the requested page
*/

require_once("php/Message.class.php");
require_once("php/Home.class.php");
require_once("php/Connexion.class.php");
require_once("php/Profil.class.php");
require_once("php/Register.class.php");
require_once("php/Voc.class.php");
require_once("php/IrregularVerbs.class.php");

class Content {

    private $_bdd;
    private $_smarty;
    private $_page;


    public function __construct($bdd, $smarty, $page) {
        $this->_bdd      = $bdd;
        $this->_smarty   = $smarty;
        $this->_page     = $page;
    }


    public function get_content() {
        $content = "";

        $this->_smarty->assign("UserConnected", $_SESSION['user_connected']);

        //if he's not connected, we redirect the user to the connect page
        if(!isset($_SESSION['user_connected']) || !$_SESSION['user_connected']) {
            if($this->_page == "register") {
                return $this->get_register();
            } else {
                return $this->get_login();
            }
        } else {
            switch($this->_page) {
                case "voc":
                    return $this->get_voc();
                    break;
                case "irr_verbs":
                    return $this->get_irr_verbs();
                    break;
                case "logout":
                    $this->logout();
                    break;
                default:
                    return $this->get_home();
            }
        }

        return $content;
    }

    private function get_login() {
        $_SESSION['user_connected'] = false;
        $connexion = new Connexion($this->_bdd, $this->_smarty);
        return $connexion->get_content();
    }

    private function get_register() {
        $register = new Register($this->_bdd, $this->_smarty);
        return $register->get_content();
    }

    private function logout() {
        session_destroy();
        unset($_SESSION['user']);
        unset($_SESSION['user_connected']);
        header("Location: index.php");
    }

    private function get_home() {
        $home = new Home($this->_bdd, $this->_smarty);
        return $home->get_content();
    }

    private function get_voc() {
        $voc = new Voc($this->_bdd, $this->_smarty);
        return $voc->get_content();
    }

    private function get_irr_verbs() {
        $irr_vb = new IrregularVerbs($this->_bdd, $this->_smarty);
        return $irr_vb->get_content();
    }

}

?>