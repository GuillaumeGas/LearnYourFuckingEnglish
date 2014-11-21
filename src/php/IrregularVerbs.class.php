<?php
class IrregularVerbs {
    private $_bdd;
    private $_smarty;

    public function __construct($bdd, $smarty) {
        $this->_bdd    = $bdd;
        $this->_smarty = $smarty;
    }

    public function get_content() {
        if(isset($_GET['opt'])) {
            if($_GET['opt'] == "add_vb") {
                if(isset($_GET['vb_posted'])) {
                    return $this->save_vb();
                } else {
                    return $this->print_add_form();
                }
            } else {
                return Message::msg("Error. Bad opt.", "irr_verbs", $this->_smarty);
            }
        } else {
            if(isset($_GET['vb_posted'])) {
                return $this->check_test();
            } else {
                return $this->show_test_form();
            }
        }
    }

    private function save_vb() {
        for($i = 0; $i < 10; $i++) {
            if(strlen($_POST['english_'.$i]) > 0) {
                $query = $this->_bdd->prepare("INSERT INTO irreg_vb VALUES(NULL, :french, :english, :preterit, :perfect)");
                $query->execute(array(":french" => $_POST['french_'.$i], ":english" => $_POST['english_'.$i], ":preterit" => $_POST['preterit_'.$i], ":perfect" => $_POST['perfect_'.$i]));
            }
        }

        return $this->_smarty->fetch("html/add_vb.html");
    }

    private function print_add_form() {
        return $this->_smarty->fetch("html/add_vb.html");
    }

    private function check_test() {
        $nb_err = 0;
        $err = "";
        for($i = 0; $i < $_POST['nb_vb']; $i++) {
            if($_POST['french_'.$i] != $_POST['correct_french_'.$i]
                || $_POST['english_'.$i] != $_POST['correct_english_'.$i]
                || $_POST['preterit_'.$i] != $_POST['correct_preterit_'.$i]
                || $_POST['perfect_'.$i] != $_POST['correct_perfect_'.$i]) {
                $nb_err++;
                $err .= $_POST['correct_english_'.$i]." - ".$_POST['correct_preterit_'.$i]." - ".$_POST['correct_perfect_'.$i]." - ".$_POST['correct_french_'.$i]."<br>";
            }
        }

        $this->_smarty->assign("Err", $nb_err);
        $this->_smarty->assign("Lang", $_POST['lang']);
        $this->_smarty->assign("ShowResult", "true");

        return $this->_smarty->fetch("html/irr_verbs.html");
    }

    private function show_test_form() {
        $lang = "french";
        if(isset($_GET['lang'])) {
            if($_GET['lang'] == "english") {
                $lang = $_GET['lang'];
            }
        }

        $query = $this->_bdd->query("SELECT * FROM irreg_vb ORDER BY RAND() LIMIT 10");
        $voc = array();
        $nb_voc = 0;
        while($data = $query->fetch()) {
            $voc[$nb_voc++] = $data;
        }

        $this->_smarty->assign("ListIrrVerbs", $voc);
        $this->_smarty->assign("Lang", $lang);
        $this->_smarty->assign("NbVerbs", $nb_voc);
        $this->_smarty->assign("ShowResult", "false");

        return $this->_smarty->fetch("html/irr_verbs.html");
    }
}

?>