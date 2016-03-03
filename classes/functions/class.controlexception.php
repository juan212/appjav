<?php

class ControlException extends Exception {
    
    private $view;
    private $data;
    
    public function __construct($message = "", $code = 0, $previous = NULL, $view = false, $data = false) {
        
        $this->view = $view;
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }
    
    public function getView() {
        return $this->view;
    }
    
    public function getData() {
        return $this->data;
    }
    
}
