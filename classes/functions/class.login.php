<?php


class Login {

    public  $login;
    private $password;
    public  $role;
    public  $prenom;

    public function __construct($identifiant, $password) {
        //print_r($identifiant);exit;
        $this->login = $identifiant;
        $this->password = $password;
    }

    public function identification(){

        Connexion::getInstance()->query("SELECT u.login, u.nom, u.statut, u.prenom FROM user u
                    WHERE u.login = \"".mysql_real_escape_string($this->login)."\" 
                    AND u.password = \"".$this->password."\"
                    LIMIT 1");

        $r = Connexion::getInstance()->fetch();
       // print_r($r);
        $this->role = $r['statut'];
        
        //echo '<pre>';
        if(Connexion::getInstance()->rows() == 1){
            $this->login = $r['nom'];
            $this->prenom = $r['prenom'];
            //print_r($r);
            return true;
        }
        
        return false;
    }
    
    public static function adminIdentification($login){

        Connexion::getInstance()->query("SELECT statut FROM user WHERE login =\"".mysql_real_escape_string($login)."\"");
        $statut = Connexion::getInstance()->result();
        
        if(Connexion::getInstance()->rows() == 1)
            return true;
        
        return false;
    }
}
?>
