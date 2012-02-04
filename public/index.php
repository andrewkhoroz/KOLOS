<?php

//error_reporting(E_ALL & ~E_NOTICE);
// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
// Define application environment
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Typically, you will also want to add your library/ directory
// to the include_path, particularly if it contains your ZF installed
set_include_path(implode(PATH_SEPARATOR, array(
            dirname(__FILE__) . '/../library/',
//            dirname(__FILE__) . '/../library/Zend',
            dirname(__FILE__) . '/../application/models',
            dirname(__FILE__) . '/../application/forms',
            'Z:/home/localhost/www/bug_tracker/library/',
            'D:/Education/PHP/ZendFramework/LIBRARY',
            get_include_path(),
        )));
/** Zend_Application */
//require_once 'Zend/Application.php';
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
                APPLICATION_ENV,
                APPLICATION_PATH . '/configs/application.ini'
);
try {
    $application->bootstrap()
            ->run();
} catch (Model_Exception_AccessDenied $ex) {
//    Zend_Debug::fdump($ex, '$ex34');
}