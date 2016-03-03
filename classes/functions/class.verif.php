<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Verif {

    public function __construct() {
        
    }

    public static function Verifmail($col, $table, $mail) {

        Connexion::getInstance()->query("SELECT ".$col." FROM " . $table . " WHERE email = '" . $mail . "'");

        if (Connexion::getInstance()->rows() == 0) {
            if (ilter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $err = 0;
                return print_r($err);
            } else {
                $err = 1;
                return print_r($err);
            }
        } else {

            $err = 2;
            return print_r($err);
        }
    }

    public static function Verifname() {
        Connexion::getInstance()->query("SELECT email FROM " . $table . " WHERE email = '" . $mail . "'");

        if (Connexion::getInstance()->rows() == 0) {
            
        }
    }

}
