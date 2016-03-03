<?php

class Account {

    private $login;
    private $id_account;
    private $clients;

    public function __construct($login) {

        $this->login = $login;
        
        Connexion::getInstance()->query('SELECT id
            FROM user WHERE login = "'.$login.'"');
        
        $this->id_account = Connexion::getInstance()->result();
        
        /*Connexion::getInstance()->query('SELECT u.nom
            FROM user u 
            WHERE u.id_user IN (
                SELECT DISTINCT ar.id_membre
                FROM account_rights ar
                LEFT JOIN account a ON a.id_account = ar.id_account
                WHERE a.pseudo = "'.$this->login.'")');
        
        $this->clients = Connexion::getInstance()->fetchAll3();*/

    }
    
    public function getID()
    {
        return $this->id_account;
    }
    
    public function getPseudo() {
        return $this->pseudo;
    }

    public function getClients() {
        return $this->clients;
    }

    public static function getAllAccounts(Client $client){
        
        $accounts = array();
        
        $query = Connexion::getInstance()->query("SELECT a.name, ar.page, a.id_account
            FROM account_rights ar
            LEFT JOIN client c ON c.id_client = ar.id_client
            LEFT JOIN account a ON a.id_account = ar.id_account
            WHERE c.id_client = '".$client->getID()."' AND a.name != '".$_SESSION['account']."'");
        
        while ($result = Connexion::getInstance()->fetch($query)){
            $accounts[$result['id_account']]['pages'][] = $result['page'];
            $accounts[$result['id_account']]['name'] = $result['name'];
        }
        
        return $accounts;
        
    }
    
    public static function recAccountPage($page, $status, Client $client, $id_account)
    {

        if($status == 'true')
            Connexion::getInstance()->query("INSERT IGNORE INTO account_rights VALUES ( ".$id_account.", ".$client->getID().", '".$page."' )");
        else
            Connexion::getInstance()->query("DELETE FROM account_rights WHERE id_account =  ".$id_account." AND id_client = ".$client->getID()." AND page = '".$page."'");

    }
    
    public static function addAccount($name, $pass, Client $client, $statut = 0){
        
        //we can't use admin
        if ($name == 'admin' OR empty($name) OR empty($pass))
            return;
        
        Connexion::getInstance()->query("INSERT IGNORE INTO account (pseudo, mdp, statut) VALUES ( '".addslashes($name)."', '".md5(addslashes($pass))."', ".$role.")");
        $id_account = Connexion::getInstance()->lastID();
        
        //if account already exists or is admin, we check wether it's the same
        if ($id_account == 0){
            
            Connexion::getInstance()->query("SELECT id_account FROM account WHERE pseudo = '".addslashes($name)."' AND mdp = '".md5(addslashes($pass))."' AND statut = 0");
            $id_account = (int)Connexion::getInstance()->result();
            
        }
        
        //if it's still 0 then we have a double
        if ($id_account == 0)
            return;
        
        Connexion::getInstance()->query("INSERT IGNORE INTO account_rights (id_account, id_membre, page) 
                    ( ".$id_account.", ".$client->getID().", 'gestion'), 
                        ( ".$id_account.", ".$client->getID().", 'recherche'),
                                ( ".$id_account.", ".$client->getID().", 'facturation')");
    }
    
    public static function removeAccount($id_account, Client $client){
        
        Connexion::getInstance()->query("DELETE FROM account_rights WHERE id_account = ".$id_account." AND id_client = ".$client->getID());
        
        Connexion::getInstance()->query("SELECT DISTINCT id_client FROM account_rights WHERE id_account = ".$id_account);
        
        if (Connexion::getInstance()->rows() == 0)
            Connexion::getInstance()->query("DELETE FROM account WHERE id_account = ".$id_account);
        
        header('location:/tools/access');
        
    }

}