<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__)) . '/../../../');


// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH),
    realpath(APPLICATION_PATH . '/library'),
    realpath(APPLICATION_PATH . '/resources')
)));

/** Zend_Application */
require_once 'MyFw/ControllerFront.php';

// Create application, bootstrap, and run
$application = new MyFw_ControllerFront(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/application.ini'
);
$application->bootstrap_api();

include("../lib/Workers/Api.php");
include("../lib/Workers/Login.php");
 
// include("index_swagger.php");

/**
 * Simple Inventory API
 * @version 1.0.0
 */


/**
 * POST addInventory
 * Summary: adds an inventory item
 * Notes: Adds an item to the system
 * Output-Formats: [application/json]
 */
$app->POST('/api/inventory', function($request, $response, $args) {
            
            $body = $request->getParsedBody();
            $response->write('How about implementing addInventory as a POST method ?');
            return $response;
            });


/**
 * GET searchInventory
 * Summary: searches inventory
 * Notes: By passing in the appropriate options, you can search for available inventory in the system 
 * Output-Formats: [application/json]
 */
$app->GET('/api/login', "WorkerLogin::login" );



$app->run();
