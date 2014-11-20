<?php


class Register {

    private $_bdd;
    private $_smarty;

    public function __construct($bdd, $smarty) {
        $this->_bdd    = $bdd;
        $this->_smarty = $smarty;
    }

    public function get_content() {
        if(isset($_POST['nickname']) && isset($_POST['password']) && isset($_POST['password_verif'])) {
            if(!$this->insert()) {
                $this->_smarty->assign("Error", "yes");
                return $this->_smarty->fetch("html/register.html");
            } else {
                $this->_smarty->assign("Registration", "ok");
                $this->_smarty->assign("Error", "no");
                return $this->_smarty->fetch("html/connexion.html");
            }
        } else {
            $this->_smarty->assign("Error", "no");
            return $this->_smarty->fetch("html/register.html");
        }
    }

    private function insert() {
        if(isset($_POST['nickname']) && isset($_POST['password']) && isset($_POST['password_verif'])) {
            if($_POST['password'] == $_POST['password_verif']) {
                return Profil::s_insert($this->_bdd, $_POST['nickname'], $_POST['password']);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

?>