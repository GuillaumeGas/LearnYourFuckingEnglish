<?php

/*
 fetch the content page according to the requested page
*/


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
        switch($this->_page) {

        }

        return $content;
    }


}

?>