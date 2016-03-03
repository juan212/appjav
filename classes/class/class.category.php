<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Category {

    function __construct() {
        
    }
    
    public static function RecCategory($post){
        
       // print_r("INSERT IGNORE INTO category (id,img_url," . $post['col'] . ") VALUES ('','" . CategoryController::RecImgArt() . "'," . $post['val'] . ")");
        
        $query = "INSERT IGNORE INTO category (id,img_url," . $post['col'] . ") VALUES ('','" . CategoryController::RecImgArt() . "'," . $post['val'] . ")";
        
        self::rec($query);
    }
    
    public static function SelCat(){
        
        $query = "SELECT id, nom FROM category";
        
        $sel = self::rec($query);
        
        $sel = Connexion::getInstance()->fetchAll();
        
        return $sel;
    }

    protected static function rec($query) {

        Connexion::getInstance()->query($query);
        
    }
    
    

}

