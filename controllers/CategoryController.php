<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CategoryController extends FrontController{
    
    public function __construct(Client $client, Account $account) {
        parent::__construct($client, $account);
        
    }
    
    public function RecArticleClean($post) {
        $val = '';
        $col = '';

        foreach ($this->PostCleaner($post) as $key => $value) {
            if ($key != 'MAX_FILE_SIZE' && $key != 'submit') {

                $col .= $key . ',';
                $val .= "'". $value . "',";
            }
        }
        $col1 = substr($col, 0, -1);
        $val1 = substr($val, 0, -1);

        $post = array();
        $post['col'] = $col1;
        $post['val'] = $val1;
        
        //echo '<pre>';print_r($post);echo '<pre>';

        Category::RecCategory($post);
    }
    
    public static function ImageArt($post) {
        // print_r($_FILES);
        if (!empty($post) && isset($post['submit'])) {

            $current = array();
            foreach ($post as $indice => $valeur) {
                if ((strtolower($indice) != strtolower($valeur)) && ($indice != 'submit')) {
                    if ($indice === 'image') {
                        $newfile = htmlentities($valeur, ENT_QUOTES, "UTF-8");
                    } else {
                        $current[$indice] = htmlentities($valeur, ENT_QUOTES, "UTF-8");
                        //print_r($current);
                    }
                }
            }

            if (!empty($_FILES['image']['name'])) {
                $dir_subida = ROOT_PATH . "web/images/category/";

                $fichero_subido = $dir_subida . basename($_FILES['image']['name']);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $fichero_subido)) {
                } else {
                    echo "¡Posible ataque de subida de ficheros!\n";
                }

                //echo 'Más información de depuración:';
                //print_r($_FILES);
            }
        }
    }
    
    public static function RecImgArt() {
        if (!empty($_FILES) && isset($_FILES) && isset($_POST['submit'])):
            $img = '/images/category/' . htmlentities($_FILES['image']['name']);
            return $img;
        endif;
    }

}
