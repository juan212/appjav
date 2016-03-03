<?php

/**
 * Description of class
 *
 * @author Juan Gonzalez
 */
class Connexion {

    private $connexion;
    private $result;
    public $query;
    private static $_instance = null;

    public function __construct() 
    {
        $this->connexion = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
		
		if (!$this->connexion)
			die('Maintenance');
		
        mysql_select_db(DB_BASE, $this->connexion);
        $this->query("SET NAMES 'UTF8'");
    }
    
    /**
     * Singleton
     * 
     * @return Instance of Connexion
     */
    
    public static function getInstance() {
        
        if (is_null(self::$_instance)) {
            self::$_instance = new Connexion();
        }
        return self::$_instance;
    }
    
    /**
     * Used for Mysql has gone away
     */
    public static function resetConnexion(){
        
        self::$_instance = new Connexion();
    }

    /**
     * return mysql_query of a query
     * 
     * @param String $query
     * @return type
     */
    public function query($query) 
    {
        
        $this->query = $query;
        
        $start = microtime(true);
        $this->result = mysql_query($query, $this->connexion);
        $end = microtime(true);
        
        //show for devs
        if (!$this->result && strlen($query)>0){
            //devs
            if (Security::isAdmin())
                echo 'SQL ERROR : '.$query;
            //users
            elseif(php_sapi_name() != 'cli')
                @trigger_error('SQL ERROR : '.mysql_error($this->connexion).' : '.nl2br($query), E_USER_WARNING);
        }
        
        //SQLStack::getInstance()->addToStack($query, round($end-$start, 2));
        return $this->result;
    }

    public function err(){

        if (mysql_errno($this->connexion))
            return true;

        return false;

    }

    public function echoQuery(){
        return $this->query;
    }

    public function fetch($result = false){
        
        
        if($result)
            return mysql_fetch_assoc($result);
        
        if ($this->result)
        return mysql_fetch_assoc($this->result);
    }

    public function fetchAll(){

        if (!$this->result)
            return;
        
        $array = array();
        while($r = mysql_fetch_assoc($this->result))
                $array[] = $r;
        return $array;
    }
    
    public function fetchKeyAll(){

        if (!$this->result)
            return;
        
        $array = array();
        while($r = mysql_fetch_assoc($this->result))
                $array[array_shift($r)] = $r;
        return $array;
    }
    
    public function fetchAllObject(){

        $array = array();
        while($r = mysql_fetch_object($this->result))
                $array[] = $r;
        return $array;
    }
    
    public function fetchObject($result = false){

        if($result)
            return mysql_fetch_object($result);
        
        return  mysql_fetch_object($this->result);
    }

    public function fetchAll2(){
        
        $array = array();
        
        while($r = mysql_fetch_assoc($this->result))
                $array = $r;
        return $array;
    }
    
    public function fetchObject2(){
        $array = array();
        while($r = mysql_fetch_object($this->result))
                $array = $r;
        return $array;
    }

    public function fetchAll3(){
        
        $array = array();
        
        while($r = mysql_fetch_row($this->result))
                $array[] = $r[0];
        return $array;
    }
    
    
    public function fetchKeyValue($escape = true){
        
        $array = array();
        
        while($r = mysql_fetch_row($this->result)){
                if ($escape)
                    $array[$r[0]] = mysql_real_escape_string($r[1]);
                else
                    $array[$r[0]] = $r[1];
        }
        
        return $array;
    }
    
    public function fetchKeyValueObject(){
        while($r = mysql_fetch_row($this->result))
                $array->$r[0] = mysql_real_escape_string($r[1]);
        return $array;
    }

    public function rows($result = false){
        
        if($result)
            return @mysql_num_rows($result);
        
        return @mysql_num_rows($this->result);
    }

    public function result($row = 0){
        if($this->rows() >= 1)
            return @mysql_result($this->result, $row);
        return false;
    }

    public function close(){
        mysql_close($this->connexion);
    }
    
    public function rowsAffected($query){
        
        $this->query($query);
        
        return @mysql_affected_rows($this->connexion);
        
    }
    
    public function maj($query){
        
        $this->query($query);
        if($this->err())
            return mysql_error ($this->connexion);
        else
            return @mysql_affected_rows($this->connexion);
        
    }
    
    public function lastID()
    {
        return (int)mysql_insert_id($this->connexion);
    }
    
    public function getResult() {
        return $this->result;
    }

}