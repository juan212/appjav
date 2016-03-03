<?php

/*********************************************************
 * IMPORTANT : NEVER MODIFY THIS FILE                    *
 * @author Juan Gonzalez <juancarlos@bytemamba.com> *
 *********************************************************/

ini_set('display_errors',1);
    error_reporting(E_ALL);
    
    

require_once '../lib/settings.php';
//everybody on shopping feed
if (DOMAIN !== 'jcgc-developpeur'){
    header('location:http://app.jcgc-developpeur.com');
    exit;
}

//remote connexion from V1
if (!Security::isLogged()){

        //$params_no_get = explode('?', $_SERVER['REQUEST_URI']);
        //$params = explode('/', $params_no_get[0]);
        //print_r($_GET);

        //redirect US users
        $lang = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $lang = strtolower(substr(chop($lang[0]),0,2));

        if (!isset($_GET['lang']) && $lang != 'fr') {
            header('location:/');
            exit;
        }

        if (file_exists(ROOT_PATH . 'lib/lang/'.preg_replace('/[^A-Za-z]/', '', $_GET['lang']).'.php')){
            $traduction = array();
            require_once(ROOT_PATH . 'lib/lang/'.preg_replace('/[^A-Za-z]/', '', $_GET['lang']).'.php');
        }

            $_GET = array_map('htmlentities', $_GET);
            $_POST = array_map('htmlentities', $_POST);
//print_r($_POST);
        add('login');
        //exit;
    }

    else {
        //session_destroy();
        //Security::logout();
        //print_r($_SESSION);
        $account = new Account($_SESSION['account']);
        $utilisateur = new Client($_SESSION['login']);
        //print_r($account);

        $traduction = array();

        $_SESSION['lang'] = isset($_SESSION['lang']) ? strtolower($_SESSION['lang']) : strtolower($utilisateur->getConfiguration('LANG'));
        $_SESSION['lang'] = empty($_SESSION['lang']) ? strtolower($utilisateur->app) : $_SESSION['lang'];

        if ($_SESSION['lang'] != 'fr' && file_exists(ROOT_PATH.'lib/lang/'.$_SESSION['lang'].'.php'))
            require_once(ROOT_PATH . 'lib/lang/'.$_SESSION['lang'].'.php');
            elseif ($_SESSION['lang'] != 'fr' && file_exists(ROOT_PATH.'lib/lang/us.php'))
            require_once(ROOT_PATH . 'lib/lang/us.php');

        $frontController = new FrontController($utilisateur, $account);
        
        $controller = $frontController->getChildController();
        //echo '<pre>';
        //print_r($frontController);
        //print_r($controller->getView());
        //echo '</pre>';
        include_once $controller->getView();

    }
