<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminController extends FrontController{
    
    public function __construct(Client $client, Account $account) {
        parent::__construct($client, $account);
    }

}
