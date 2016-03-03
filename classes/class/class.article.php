<?php


class Article {

    public $user = '';
    public $content = "";
    public $title = "";
    public $subtitle = "";
    public $date = "";
    public $image = "";

    public function __construct() {
        
    }

    public static function RacArticle($post) {

        //print_r(self::VrisfArt($post));
        // print_r("INSERT INTO article (id,img_url,".$post['col'].",date ) VALUES ('','".ArticleController::RecImgArt()."',".$post['val'].",'".  @date('d-m-Y')."')");
        if (self::VrisfArt($post) == TRUE && !empty($_POST) && isset($_POST['submit'])) {
            $query = "INSERT IGNORE INTO article (id,img_url," . $post['col'] . ",date ) VALUES ('','" . ArticleController::RecImgArt() . "'," . $post['val'] . ",'" . @date('d-m-Y') . "')";
            //print_r(self::VrisfArt($post));
            //print_r($query);
            self::rec($query);

            echo '<div class="alert alert-success" role="alert" style=" position: absolute; top:80px; right:10px"">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Créé:</span>
                        Succes
                  </div>';
        } elseif(self::VrisfArt($post) === FALSE && empty($_POST) && !isset($_POST['submit'])) {

            echo '<div class="alert alert-danger" role="alert" style=" position: absolute; top:80px; right:10px;">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                        L\'article existe deja
                  </div>';
        }
    }

    protected static function rec($query) {

        Connexion::getInstance()->query($query);
        
    }

    protected static function VrisfArt() {
        //print_r($_POST);
        if (!empty($_POST) && isset($_POST['submit'])) {
            $post = $_POST['title'];
            $queryQ = "SELECT title FROM article WHERE title = '" . $post ."'";
            //$query = Connexion::getInstance()->query($queryQ);
            $query = self::rec($queryQ);
            //$query = Connexion::getInstance()->fetchAll();
            
            $test = Connexion::getInstance()->rows() !== 1;
            print_r($test);

            if (Connexion::getInstance()->rows() === 1) {

                return FALSE;
            } else {
                
            print_r('test');
                return TRUE;
            }
        }
    }

}
