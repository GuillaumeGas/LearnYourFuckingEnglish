<?php


class Inscription {

    public static function insert($bdd) {
        if(isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['password_verif'])) {
            if($_POST['password'] == $_POST['password_verif']) {
                if(!Profil::s_insert($bdd, $_POST['pseudo'], $_POST['password'], 1, 2)) {
                    return false;
                } else {
                    $data = Profil::s_search_byName($bdd, $_POST['pseudo']);
                    $_SESSION['connected'] = true;
                    $_SESSION['user']      = $data;
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

?>