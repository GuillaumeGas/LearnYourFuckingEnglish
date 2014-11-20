<?php

require_once("php/Login.class.php");

/**
 * Classe permettant à l'utilisateur de se connecter
 */

class Connexion {

    private $_bdd;       //représente la connexion à la bdd
    private $_smarty;    //représente l'objet smarty (moteur de template)

    public function __construct($bdd, $smarty) {
        $this->_bdd       = $bdd;
        $this->_smarty    = $smarty;
    }

    /**
     * @brief Renvoie les éléments à afficher en fonction du status de la connexion (formulaire, message de réussite ou d'échec..)
     * @return mixed
     */
    public function get_content() {
        //objet permettant la connexion
        $l = new Login("", "users", $this->_bdd, true);
        $l->addChamp("Nickname", "nickname", "text");
        //$l->addChamp("Nickname", "Nickname", "password", true);
        $l->addChamp("Password", "password", "password");

        $l->login();

        //si des données ont été envoyées...
        if($l->connexion_ok()) {
            header("Location: index.php");
        } else if(!$l->donnees_envoyees()) {
            $this->_smarty->assign("Error", "no");
            $this->_smarty->assign("Registration", "no");
            return $this->_smarty->fetch("html/connexion.html");
        } else {
            $this->_smarty->assign("Error", "yes");
            $this->_smarty->assign("Registration", "no");
            return $this->_smarty->fetch("html/connexion.html");
        }
    }

}

?>