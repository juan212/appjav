<?php session_start();
 
set_time_limit(600);
ini_set('auto_detect_line_endings', true);
ini_set('memory_limit', '120M');
ini_set('memory_limit', '-1');

/**
 * Defines
 */  

define ('HOST', $_SERVER['HTTP_HOST']);
$hosts = explode('.', HOST);
$subDomain = $hosts[0];
define('DOMAIN', $hosts[1]);

//print_r();
if (strpos($subDomain, 'app') !== false){
	
	//define('SF_DEV', true);
	define('DB_HOST', 'db613230787.db.1and1.com'); 
	define('APP_PATH', 'http://app.jcgc-developpeur.com');
	//define('TRACK_URI', 'http://tag-dev.shopping-feed.com/');
	//define('FEED_URI', 'http://export-dev.shopping-feed.com/');
	//define('WS_URI', 'http://ws-dev.shopping-feed.com/');
	define('COMMAND_PATH', '/home/web');
	//define('STRIPE_API_KEY', 'sk_test_UwgP35iqgnzpxBAh8JiV4ZOd');
}

else {
	
	//define('SF_DEV', false);
	define('DB_HOST', 'db613230787.db.1and1.com'); 
	define('APP_PATH', 'http://app.jcgc-developpeur.com');
	//define('TRACK_URI', 'http://tag.shopping-feed.com/');
	//define('FEED_URI', 'http://export.shopping-feed.com/');
	//define('WS_URI', 'http://ws.shopping-feed.com/');
	define('COMMAND_PATH', '/home');
	//define('STRIPE_API_KEY', 'sk_live_3nFURMg27efbsC4SkfhGqfZW');
}

define('DB_USER', 'dbo613230787');
define('DB_PASS', 'caracas212');
define('DB_BASE', 'db613230787');

define('SITE', 'test back antoine');
define('ROOT_PATH', dirname(__FILE__).'/../');
define('FEED_PATH', ROOT_PATH.'web/export/');

define('TVA', @date('Y')>2013 ? 20 : 19.6);
//define('SHOPIFY_API_KEY', '9b892ccb0b6c7e34477b3158eac4366a');
//define('SHOPIFY_SECRET', '12f7e83aa67a78c62380201207538a50');
/** 
 * Amazon 
 */

define('APPLICATION_NAME', 'test back Javier');
define('APPLICATION_VERSION', '1');

/**
 * eBay
 */
//define('EBAY_VERSION', 717);

/**
 * Google OAuth2
 */
//define('OAUTH2_CLIENT_ID', '221504438988-l982p99polmqug5j23i22ngedimvprtu.apps.googleusercontent.com');
//define('OAUTH2_CLIENT_SECRET', 'gGfS39LNy4r8mp17CA1mdbhp');
//define('OAUTH2_REDIRECT_URI', APP_PATH . 'googleshopping/gettoken/');

/**
 * Bing OAuth
 */
//define('BING_CLIENT_ID', '0000000044133B96');
//define('BING_CLIENT_SECRET', 'SkjwgnpxcasjefQ1ZpVJX26AdLmOJ5kP');
//define('BING_REDIRECT_URI', APP_PATH . 'bing/gettoken/');

/**
 * Functions
 */

require_once ROOT_PATH.'lib/functions.php';
spl_autoload_register('defaultAutoload');
register_shutdown_function('handleErrors');

if (Security::isAdmin()){
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
}
else {
    ini_set('display_errors',0);
    error_reporting(0);
}


