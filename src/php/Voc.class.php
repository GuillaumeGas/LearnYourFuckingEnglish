<?php

class Voc {
    private $_bdd;
    private $_smarty;

    public function __construct($bdd, $smarty) {
        $this->_bdd    = $bdd;
        $this->_smarty = $smarty;
    }

    public function get_content() {
        if(isset($_GET['opt'])) {
            if($_GET['opt'] == "add_xords") {
                if(isset($_GET['words_posted'])) {
                    return $this->save_words();
                } else {
                    return $this->print_add_form();
                }
            } else {
                return Message::msg("Error. Bad opt.", "voc", $this->_smarty);
            }
        } else {
            if(isset($_GET['words_posted'])) {
                return $this->check_test();
            } else {
                return $this->show_test_form();
            }
        }
    }

    private function save_words() { return ""; }
    private function print_add_form() { return ""; }

    private function check_test() { return ""; }
    private function show_test_form() { return ""; }
}

?>