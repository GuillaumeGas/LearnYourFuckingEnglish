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

    private function save_words() {
        for($i = 0; $i < 10; $i++) {
            if(strlen($_POST['english_'.$i]) > 0) {
                $query = $this->_bdd->prepare("INSERT INTO voc VALUES(NULL, :english, :french)");
                $query->execute(array(":english" => $_POST['english_'.$i], ":french" => $_POST['french_'.$i]));
            }
        }

        return $this->_smarty->fetch("html/add_voc.html");
    }
    private function print_add_form() {
        return $this->_smarty->fetch("html/add_voc.html");
    }

    private function check_test() {
        $nb_err = 0;
        $err = "";
        for($i = 0; $i < $_POST['nb_vb']; $i++) {
            if($this->word_already_read(0, $_POST['id_'.$i], $this->_bdd) == 0) {
                $this->add_word_already_read(0, $_POST['id_'.$i], $this->_bdd);
            }
            if($_POST['translate_'.$i] != $_POST['correct_translate_'.$i]) {
                $nb_err++;
                $err .= $_POST['origin_'.$i]." != ".$_POST['translate_'.$i]." => ".$_POST['correct_translate_'.$i]."<br>";
                $this->add_failure(0, $_POST['id_'.$i], $this->_bdd);
            } else {
                $this->add_success(0, $_POST['id_'.$i], $this->_bdd);
            }
        }

        $this->_smarty->assign("Err", $err);
        $this->_smarty->assign("NbErr", $nb_err);
        $this->_smarty->assign("Lang", $_POST['lang']);
        $this->_smarty->assign("ShowResult", "true");

        return $this->_smarty->fetch("html/voc.html");
    }

    private function show_test_form() {
        $lang = "french";
        if(isset($_GET['lang'])) {
            if($_GET['lang'] == "english") {
                $lang = $_GET['lang'];
            }
        }

        // 1 : prendre 5 nouveaux mots
        // 2 : prendre 5 mots déjà connus (% de réussite > 80)
        // compléter avec des mots pas bien connus

        $query_1 = $this->_bdd->prepare("SELECT * FROM voc WHERE id NOT IN (SELECT id_word FROM voc_already_read WHERE id_user = :id_user) LIMIT 5");
        $query_1->execute(array(":id_user" => 0));

        $voc = array();
        $nb_voc = 0;
        while($data = $query_1->fetch()) {
            $voc[$nb_voc] = $data;
            $voc[$nb_voc++]['success'] = 0;
        }

        $query_2 = $this->_bdd->prepare("SELECT V.id, V.english, V.french, VAR.id_word, VAR.success, VAR.failures FROM voc V, voc_already_read VAR WHERE V.id = VAR.id_word AND VAR.id_user = 0 ORDER BY RAND()");
        $query_2->execute(array(":id_user" => 0));

        $tmp_voc = array();
        $nb_already_read = 0;
        while($data = $query_2->fetch()) {
            $tmp_voc[$nb_already_read] = $data;
            $tmp_voc[$nb_already_read]["success"] = ($data['success']/($data['success']+$data['failures']))*100;
            $nb_already_read++;
        }

        $nb_word_already_know = 0;
        $i = 0;
        while($i < $nb_already_read && $nb_word_already_know < 5) {
            if($tmp_voc[$i]['success'] >= 80) {
                $voc[$nb_voc++] = $tmp_voc[$i];
                $nb_word_already_know++;
            }
            $i++;
        }

        $i = 0;
        while($nb_voc < 20 && $i < $nb_already_read) {
            if($tmp_voc[$i]['success'] < 80) {
                $voc[$nb_voc++] = $tmp_voc[$i];
            }
            $i++;
        }

        $this->_smarty->assign("ListVoc", $voc);
        $this->_smarty->assign("Lang", $lang);
        $this->_smarty->assign("NbVoc", $nb_voc);
        $this->_smarty->assign("ShowResult", "false");

        return $this->_smarty->fetch("html/voc.html");
    }


    private function select_word($id_user, $id_word, $bdd) {
        $query = $bdd->prepare("SELECT * FROM voc_already_read WHERE id_word = :id_word AND id_user = :id_user");
        $query->execute(array(":id_word" => $id_word, ":id_user" => $id_user));
        return $query->fetch();
    }

    private function word_already_read($id_user, $id_word, $bdd) {
        $query = $bdd->prepare("SELECT COUNT(*) AS nb FROM voc_already_read WHERE id_word = :id_word AND id_user = :id_user");
        $query->execute(array(":id_word" => $id_word, ":id_user" => $id_user));
        $nb = $query->fetch()['nb'];
        return $nb;
    }

    private function add_word_already_read($id_user, $id_word, $bdd) {
        $query = $bdd->prepare("INSERT INTO voc_already_read VALUES(:id_user, :id_word, 0, 0)");
        $query->execute(array(":id_word" => $id_word, ":id_user" => $id_user));
    }

    private function add_failure($id_user, $id_word, $bdd) {
        $data_word = $this->select_word($id_user, $id_word, $bdd);
        $failures = $data_word['failures'] + 1;
        $query = $bdd->prepare("UPDATE voc_already_read SET failures = :failures WHERE id_word = :id_word AND id_user = :id_user");
        $query->execute(array(":id_word" => $id_word, ":id_user" => $id_user, ":failures" => $failures));
    }

    private function add_success($id_user, $id_word, $bdd) {
        $data_word = $this->select_word($id_user, $id_word, $bdd);
        $success = $data_word['success'] + 1;
        $query = $bdd->prepare("UPDATE voc_already_read SET success = :success WHERE id_word = :id_word AND id_user = :id_user");
        $query->execute(array(":id_word" => $id_word, ":id_user" => $id_user, ":success" => $success));
    }
}

?>