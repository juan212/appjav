<?php

class Client {

    public $configuration;
    private $login;
    public $infosClient = array();
    public $facturation_paybox;
    public $facturation;
    public $number_of_products;
	public $number_of_products_total;
    public $card_expired = false;
    public $panier;




    /** @var $timeZone Default timezone for date display */
    public $timeZone;
    /** @var $app Country for publishing */
    public $app;
    /** @var $currency Currency for app */
    public $currency;
	/** @var $currency Currency for invoicing */
    public $invoice_currency;
    /**
     * Constructeur Client
     *
     * @param $nom login
     */ 
    public function __construct($login = FALSE) {
        
        $db = Connexion::getInstance();
        
        $login == TRUE ? $this->login = $login : $this->login = '';
        
        $db->query("SELECT * FROM user WHERE login ='$this->login'");
        

        $data = $db->fetch();
        
        //print_r($data);
        foreach ($data as $key => $value)
            $this->infosClient[$key] = $value;
    }

   
    
    /**
     * Getter
     *
     * @return string nom du client
     */
    public function getLogin() {

        return $this->login;
    }

    /**
     * Getter
     *
     * @return array infos du client
     */
    public function getInfosClient($field = "") {

        if ( $field != "" )
            return $this->infosClient[$field];
        else
            return $this->infosClient;
    }

    public function getID() {

        return $this->infosClient['id_user'];
    }
    
    public function unsetClient($idclient){
        
        Connexion::getInstance()->query("DELETE FROM user 
                                        WHERE id_user = ".$idclient);
    }

    

    public function getConfiguration($name = false, $cache = true){
        
        if ($name != false && isset($this->configuration[$name]) && $cache == true)
            return $this->configuration[$name];
        
        elseif($name != false){
            Connexion::getInstance()->query("SELECT value FROM configuration WHERE id_membre = ".$this->getID()." AND name = \"".$name."\"");
            $this->configuration[$name] = Connexion::getInstance()->result();
            return $this->configuration[$name];
            
        }
        else{
            Connexion::getInstance()->query("SELECT name, value FROM configuration WHERE id_client = ".$this->getID());
            $this->configuration = Connexion::getInstance()->fetchKeyValue();

            return $this->configuration;
        }
        
    }
    
    public static function setConfigurationStatic($client, $name, $value){
            
        Connexion::getInstance()->query("INSERT INTO configuration (id_membre, name, value) VALUES
            (SELECT id_membre FROM client WHERE pseudo  = '".mysql_real_escape_string($client)."', '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($value)."'
                
                ON DUPLICATE KEY UPDATE value = '".mysql_real_escape_string($value)."')");
        
    }
    
    public static function setConfigurationStaticById($client, $name, $value){
            
        Connexion::getInstance()->query("INSERT INTO configuration (id_membre, name, value)
            SELECT id_membre, '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($value)."'
                FROM client WHERE id_membre = '".mysql_real_escape_string($client)."'
                ON DUPLICATE KEY UPDATE value = '".mysql_real_escape_string($value)."'");
        
    }
    
    public static function GetConfigurationStatic($client, $name){
        
      Connexion::getInstance()->query("SELECT c.value FROM configuration c, membre m
          WHERE m.pseudo = '".mysql_real_escape_string($client)."'
            AND c.name = '".mysql_real_escape_string($name)."'
              AND c.id_membre = m.id_membre");
      return Connexion::getInstance()->result();
      
    }
    
    public function hasConfiguration($name){
      Connexion::getInstance()->query("SELECT id_configuration FROM configuration WHERE id_membre = ".$this->getID()." AND name = \"".$name."\"");
      if((int)Connexion::getInstance()->result() > 0)
        return true;
      else
        return false;
    }
    
    
    
    public function setPanier($name, $value, $date){
            
        Connexion::getInstance()->query("INSERT INTO panier (id_membre, name, value, date)
            VALUES (".$this->getID().", '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($value)."', '".mysql_real_escape_string($date)."')
                    ON DUPLICATE KEY UPDATE name = '".mysql_real_escape_string($name)."'");
        
    }
    
    public function setConfiguration($name, $value){
            
        Connexion::getInstance()->query("INSERT INTO configuration (id_membre, name, value)
            VALUES (".$this->getID().", '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($value)."')
                ON DUPLICATE KEY UPDATE value = '".mysql_real_escape_string($value)."'");
        
    }
    
    public function getPanier(){
        
        $panierQ = Connexion::getInstance()->query("SELECT value FROM panier WHERE id_membre = ".$this->getID());
         
        $panier = Connexion::getInstance()->fetchAll();
        
        return $panier;
    }
    
    public function unsetPanier($name){
            
        Connexion::getInstance()->query("DELETE FROM panier 
            WHERE id_membre = ".$this->getID()." 
            AND name = '".mysql_real_escape_string($name)."'");
        
        return TRUE;
        
    }
    
    public function unsetAllPanier(){
            
        Connexion::getInstance()->query("DELETE FROM panier 
            WHERE id_membre = ".$this->getID()." 
            AND name LIKE 'PANIER_%'");
        
        return TRUE;
        
    }

    public function setConfigurationUnique($name, $value){
            
        Connexion::getInstance()->query("INSERT IGNORE INTO configuration (id_membre, name, value)
            VALUES (".$this->getID().", '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($value)."')");
        
    }
    
    public function unsetConfiguration($name){
            
        Connexion::getInstance()->query("DELETE FROM configuration 
            WHERE id_membre = ".$this->getID()." 
            AND name = '".mysql_real_escape_string($name)."'");
        
    }
    
    public static function getClientList(){
        
        Connexion::getInstance()->query("
            SELECT pseudo FROM membre");
        
        return Connexion::getInstance()->fetchAll3();
        
    }

    public static function isClient($pseudo){
        
        Connexion::getInstance()->query("SELECT pseudo FROM membre WHERE pseudo = '". addslashes($pseudo) ."'");
        
        return Connexion::getInstance()->rows();
        
    }
    
    

    public static function getNameById($id_membre) {
        Connexion::getInstance()->query("SELECT $pseudo FROM membre WHERE id_membre=$id_membre");
        return Connexion::getInstance()->result();
    }
    
    public static function getIdByPseudo($pseudo) {
        Connexion::getInstance()->query("SELECT id_membre FROM membre WHERE pseudo=\"$pseudo\"");
        return Connexion::getInstance()->result();
    }
    
    public static function getAllClient(){
        
        $allClientQ = Connexion::getInstance()->query('SELECT * FROM membre');
        
        $allClient = Connexion::getInstance()->fetchAll($allClientQ);
        
        return $allClient;
    }
    
    public function changeStatusCleint($clientId){
        
        Connexion::getInstance()->query("SELECT statut FROM membre WHERE id_membre = ".$clientId);
        
        $clientStatut = Connexion::getInstance()->result();
        if($clientStatut == "1"){
        
            Connexion::getInstance()->query('UPDATE membre
                                             SET statut = 0
                                             WHERE id_membre = '.$clientId);
            
        } else {
            
            Connexion::getInstance()->query('UPDATE membre
                                             SET statut = 1
                                             WHERE id_membre = '.$clientId);
        }
    }
    
 
}

?>
