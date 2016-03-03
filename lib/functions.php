<?php
ini_set('display_errors',1);
    error_reporting(E_ALL);

class SimpleXMLExtended extends SimpleXMLElement {
    
        public function addCData($cdata){
                $node= dom_import_simplexml($this);
                $owner = $node->ownerDocument;
                $node->appendChild($owner->createCDATASection($cdata));
        }
}

function defaultAutoload($class_name) {
    //print_r(ROOT_PATH.'classes/db/class.'. strtolower($class_name) .'.php </br>');
    if ((file_exists(ROOT_PATH.'controllers/'. $class_name .'.php')))
        require_once ROOT_PATH.'controllers/'. $class_name .'.php';
    
    elseif ((file_exists(ROOT_PATH.'classes/db/class.'. strtolower($class_name) .'.php')))
        require_once ROOT_PATH.'classes/db/class.'. strtolower($class_name) .'.php';
    
    elseif ((file_exists(ROOT_PATH.'classes/functions/class.'. strtolower($class_name) .'.php')))
        require_once ROOT_PATH.'classes/functions/class.'. strtolower($class_name) .'.php';
    
    elseif ((file_exists(ROOT_PATH.'classes/class/class.'. strtolower($class_name) .'.php')))
        require_once ROOT_PATH.'classes/class/class.'. strtolower($class_name) .'.php';
    
    elseif ((file_exists(ROOT_PATH.'classes/export/class.'. strtolower($class_name) .'.php')))
        require_once ROOT_PATH.'classes/export/class.'. strtolower($class_name) .'.php';
    
}
    
function add($fileName) {
    
    global $controller, $utilisateur, $params, $lang;
    
    try{
        
        //for login
        if (!is_object($utilisateur)){
            $utilisateur = new stdClass();
        }
        
        if(!is_file(ROOT_PATH.'views/extends/'.$fileName.'.php') && !is_file(ROOT_PATH.'views/_us/extends/'.$fileName.'.php'))
            //throw new LokisalleException('File views/extends/'.$fileName.'.php does not exist in '.realpath ($controller->getView()), 404);
        
        $view = ROOT_PATH.'views'.DIRECTORY_SEPARATOR.'extends'.DIRECTORY_SEPARATOR.$fileName.'.php';
        
        //echo include_once $view; 
            include_once ROOT_PATH.'views'.DIRECTORY_SEPARATOR.'extends'.DIRECTORY_SEPARATOR.$fileName.'.php';
        
    } catch (LokisalleException $e) {
        
        $controller->setMessage($e->getMessage());
        include_once ROOT_PATH.'views/extends/error.php';
        
    }
}

    
function handleErrors() {
    
    $types = array (
        //E_WARNING,
        E_ERROR,
        E_USER_ERROR,
        E_USER_WARNING,
        E_PARSE,
    );
    
    $error = error_get_last();
    
    if($error !== NULL && in_array($error['type'], $types)){
        array_unshift($error, php_sapi_name());
        array_unshift($error, @date('d/m/Y H:i:s'));
        @error_log (implode (';', $error)."\r\n", 3, ROOT_PATH.'logs/errors.csv');
    }
            
}

    

?>
