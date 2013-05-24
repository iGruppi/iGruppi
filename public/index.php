<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

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
$application->bootstrap()
            ->run();
 