<?php


class Home {

    private $_bdd;
    private $_smarty;

    public function __construct($bdd, $smarty) {
        $this->_bdd    = $bdd;
        $this->_smarty = $smarty;
    }

    public function get_content() {
        return $this->_smarty->fetch("html/home.html");
    }


}

?>