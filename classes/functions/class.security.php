<?php

ini_set('display_errors',1);
    error_reporting(E_ALL);
class Security
{
    public static function isLogged()
    {
        if (!isset($_SESSION['login']))
            return Security::login(); 
        else
            return true;
    }
    
    public static function login() {
        if(!isset($_POST['login']) || !isset($_POST['password']))
            //print_r($_POST['login']);
            return Security::loginGet();
        //print_r($_POST['pseudo']); 
        $client = new Login(addslashes($_POST['login']), addslashes($_POST['password']));
        
        if ($client->identification()){
            //for agency
            //print_r('user');
            $_SESSION['login'] = $_POST['login'];
            $_SESSION['account'] = $client->login; 
            $_SESSION['statut'] = $client->role;
            $_SESSION['nom'] = $client->role;
            $_SESSION['panier'] = array();;
            
            //$_SESSION['panier'] = array();
            header ('location:/');
            
            
            
            return true;
        }
        
        return false;
    }
    
    /*
     * remote connection from V1
     */
    
    private static function loginGet(){
        
        if(!isset($_POST['login']) || !isset($_POST['password']))
            return false;
        
        $client = new Login(addslashes($_POST['login']), addslashes($_POST['password']));
        if($client->identification()){
            //for agency
            $_SESSION['account'] = $_POST['login'];
            $_SESSION['login'] = $client->login;
            
            if (!Security::isAdmin()){
                $date = Client::GetConfigurationStatic($_SESSION['login'], 'LAST_CONNEXION');
                Client::setConfigurationStatic($_SESSION['login'], 'BEFORE_LAST_CONNEXION', $date);
                Client::setConfigurationStatic($_SESSION['login'], 'LAST_CONNEXION', date('Y-m-d H:i:s'));
                header ('location:/');
            }else
                header ('location:/admin');
            
            exit;
        }
        
        return false;
    }
    
    public static function adminLogin($login, $account = false)
    {
        if(!isset($login))
            return false;
        
        if(Login::adminIdentification($login)){
            $_SESSION['account'] = $account != false ? $account : $login;
            $_SESSION['login'] = $client->login; 
            return true;
        }
        
        return false;
    }
    
    public static function logout()
    {
        session_unset();
        session_destroy();
        
        header('location:'.APP_PATH);
        //exit;
    }
    
    public static function  isAdmin()
    {
        Connexion::getInstance()->query("SELECT login FROM user WHERE statut !=0");
        $admin_accounts = Connexion::getInstance()->fetchAll3();
        //print_r($admin_accounts);  
        if (!empty($_SESSION['statut'])) {
            
        
		if (!empty($_SESSION) && $_SESSION['statut'] == 1) 
                    return TRUE;
                else
                    return false;
        }
        
    }
    
    public static function getAdminLogin(){
        
        return $_SESSION['statut'];
        
    }
    
    public static function isSuperAdmin(){
        
        if (!Security::isAdmin() || $_SESSION['admin_role'] == 1)
            return true;
        
        return false;
        
    }
    
    public static function anonimus(){
        
            if (Security::isAdmin() == FALSE || $_SESSION['statut'] == 2)
            return true;
        
        return false;
        
    }
    
    public static function hasRights($request){
        
        //we can put admin there as AdminController override access rights
        
        //pages
        if(!isset($_SESSION) && empty($_SESSION)){
            if (array_key_exists($request, Tools::getPagesNoLogged())) {
                return true;
            }   else  {
                
                return false;    
            }
        } elseif (isset($_SESSION) && @$_SESSION['statut'] == 0){
            if (array_key_exists($request, Tools::getPagesLogged())) {
                return true;
            }   else  {
                
                return false;    
            }
        } elseif (isset($_SESSION) && $_SESSION['statut'] == 1){
            if (array_key_exists($request, Tools::getPagesAdmin())) {
                return true;
            }   else  {
                
                return false;    
            }
        }
        
        
    }
    
    public static function Registrer(){
        //print_r($_POST);
        
         if (!empty($_POST['register'])){
            
            $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']);
            
            if (!$verif_caractere && !empty($_POST['pseudo']))
                return FALSE;
            if(strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 14) 
                return FALSE;
            if(strlen($_POST['mdp']) < 4 || strlen($_POST['mdp']) > 14)
                return FALSE;
            
            if ($_POST['pseudo'] == TRUE && $_POST['mdp'] == TRUE)
                $newClient = new Register ($_POST['pseudo'], $_POST['mdp'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['adresse'], $_POST['ville'], $_POST['cp'], $_POST['sexe']);
                    
                if($newClient->clientCreation()){
                    
                }
        }
    }
    
}
