<?php
/**************************************
 * IMPORTANT : NEVER MODIFY THIS FILE *
 **************************************/

ini_set('display_errors',1);
    error_reporting(E_ALL);
    

class FrontController
{
    protected $view;
    protected $viewOverride;
    protected $params;
    protected $controller_name;
    private   $child_controller;
    protected $client;
    protected $account;
    protected $action_name;
    protected $data;
    protected $post_data;
    protected $get_data;
    protected $brand;
    protected $server_data;
    protected $help;
    public $title;
    public $panierCount;
    public $postclean = array();


    public function __construct(Client $client, Account $account)
    {
        $this->client          = $client;
        $this->account         = $account;
        $this->params          = explode('/', $_SERVER['REQUEST_URI']);
        $this->post_data       = $_POST;
        $this->get_data        = $_GET;
        $this->controller_name = !empty($this->params[1]) ? $this->params[1] : 'index';
        $this->title = $this->params[1];
        //$this->postclean = array();
        
        //$panierCount = new Panier($client);
        //$panierCount = $panierCount->recPanier();
        //$this->panierCount = count($panierCount);
        
        //print_r($this->params);
        
        if(!is_object($this->data)) 
            $this->data = new stdClass();
        
        (get_class($this) == 'FrontController') ? $this->getController() : $this->getAction();
    }
    
    public function getController(){
        
        try { $controller_classname = ucfirst(strtolower($this->controller_name)).'Controller';
            
            $this->child_controller = new $controller_classname($this->client, $this->account);
            $this->view = $this->child_controller->setView();
        }
        
        catch (ReflectionException $e){
            $this->data->message = 'Fichier non trouvé';
            $this->data->errorcode = 404;
            //_r($_SERVER);
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            $this->view =  ROOT_PATH.'views/extends/error.php';
        }
        catch (ControlException $e){
            
            switch ($e->getCode()){
                case 403 :
                    header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
                    break;
                case 404 :
                default:
                    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                    break;
            }
            
            if ($e->getView()){
                $this->data = $e->getData();
                $this->view =  $e->getView();
            }
            elseif (!is_object($this->child_controller)){
                $this->child_controller->data->message = $e->getMessage();
                $this->child_controller->data->errorcode = $e->getCode();
                $this->child_controller->view =  ROOT_PATH.'views/extends/error.php';
            }
            else {
                $this->data->message = $e->getMessage();
                $this->data->errorcode = $e->getCode();
                $this->view =  ROOT_PATH.'views/extends/error.php';
            }
        }
    }
    
    protected function getAction()
    {
        if (strtolower($this->params[1])=='index' && (empty($this->params[2]) || strtolower($this->params[2]) == 'index')){
            header('location:/');
            exit;
        }
            
        //redirect users if index is set in url
        if (isset($this->params[2]) && strtolower($this->params[2]) == 'index' && !isset($this->params[3])){
            header('location:/'.$this->controller_name);
            exit;
        }
        
        $this->action_name = (!empty($this->params[2])) ? $this->params[2] : 'index';
        
        if (strpos($this->action_name, '?') !== false)
            $this->action_name = substr($this->action_name, 0, strpos($this->action_name, '?'));
        
        $action_classname = ucfirst($this->action_name).'Action';
        
        //redirect users if params not allowed
        $r = new ReflectionMethod($this, $action_classname);
        
        $params = $r->getParameters();
        
        if(!empty($this->params[3]) && isset($params[0]) && !is_object($params[0])){
            
            header('location:/'.$this->controller_name.'/'. $this->params[2]);
            exit;
        }

        //redirect if no params not allowed
        elseif(empty($this->params[3]) && isset($params[0]) && is_object($params[0]) && !$params[0]->isOptional()){
        
            header('location:/'.$this->controller_name.'/'); 
            exit;
        }
        
        //ajax's action not allowed if xmlhttprequest is not set
        elseif (substr(strtolower($action_classname), 0, 4) == 'ajax' && !isset($_SERVER['HTTP_X_REQUESTED_WITH']))
            throw new ControlException('Action forbidden without Ajax.', 403);
        
        elseif (method_exists($this, $action_classname) && !empty($this->params[3]) && count($this->params) == 4)
            $this->$action_classname($this->params[3]);
        
        elseif (method_exists($this, $action_classname) && !empty($this->params[3]) && !empty($this->params[4]) && count($this->params) == 5)
            $this->$action_classname($this->params[3], $this->params[4]);
        
        elseif (method_exists($this, $action_classname) && !empty($this->params[3]) && !empty($this->params[4]) && !empty($this->params[5]))
            $this->$action_classname($this->params[3], $this->params[4], $this->params[5]);
        
        elseif (method_exists($this, $action_classname))
            $this->$action_classname();
        
        else
            throw new ControlException($action_classname. ' in ' .  get_class($this). ' does not exist.', 404);
    }
                
    protected function IndexAction() {  }
    
    protected function LogoutAction()
    {
        Security::logout();
    }
    
    private function setView()
    {
        //override views 
        if (isset($this->view)) 
            return;
        
        if ($this->controller_name == '') {
            
            $this->view = ROOT_PATH.'views/index/index.php';
            
        }
        else {
            
            $this->view = ROOT_PATH.'views/'.strtolower($this->controller_name).'/'.strtolower($this->action_name).'.php';
            
        }
               
        if (!is_file($this->view))
            throw new ControlException('File views/'.strtolower($this->controller_name).'/'.strtolower($this->action_name).'.php does not exist.', 404);
    }

    public function getView()
    {
        return $this->view;
    }
    
    public function getData() 
    {
        return $this->data;
    }
    
    public function getHelp()
    {
        return $this->help;
    }
    
    public function getChildController()
    {
        return (is_object($this->child_controller)) ? $this->child_controller : $this;
    }
    
    /**
     * Retourne client courant
     * @return Client
     */
    
    public function getBrand() {
        return $this->brand;
    }
    
    public function getClient() {
        return $this->client;
    }
    
    public function getAccount() {
        return $this->account;
    }
    
    public function getControllerName() 
    {
        return $this->controller_name;
    }

    public function getActionName() 
    {
        return $this->action_name;
    }
    
    public function setMessage($message)
    {
        $this->data->message = $message;
    }
    
    public function getPostData() 
    {
        return $this->post_data;
    }
    
    public function getGetData() 
    {
        return $this->get_data;
    }
    
    public function getParamName()
    {
        if(!empty($this->params[3]))
            return str_replace('=', '', $this->params[3]);
        
        return false;
    }
    
    protected function getParams() 
    {
        return $this->params;
    }
    
    public function PostCleaner($post){
        //$this->postclean = array();
        foreach ($post as $key => $value) {
            
            $this->postclean[$key] = htmlentities($value);
        }
        
        return $this->postclean;
    }
    
    

}
