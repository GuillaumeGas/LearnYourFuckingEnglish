<?php

class Profil {

    private $_bdd;
    private $_data;
    private $_nb;

    public function __construct($bdd) {
        $this->_bdd = $bdd;

    }


    public function get_content() {

    }

    public static function s_insert($bdd, $nickname, $password) {
        if(!Profil::s_exist($bdd, $nickname)) {
            $query = $bdd->prepare("INSERT INTO users VALUES (NULL, :nickname, :password)");
            $query->execute(array(":nickname" => $nickname, ":password" => md5($password)));
            return $query->rowCount() == 1;
        } else {
            return false;
        }
    }

    public static function s_exist($bdd, $nickname) {
        $query = $bdd->prepare("SELECT * FROM users WHERE nickname = :nickname");
        $query->execute(array(":nickname" => $nickname));
        return $query->rowCount() == 1;
    }

}


?>
