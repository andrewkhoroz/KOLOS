<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}

define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once 'TestConfiguration.php'; 

require_once 'controllers/AllTests.php';
require_once 'models/AllTests.php';

class AllTests
{
    public static function main()
    {
        $parameters = array();
        if (extension_loaded('xdebug')) {
            $parameters['reportDirectory'] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'coverage_report';
        }
        PHPUnit_TextUI_TestRunner::run(self::suite(), $parameters);
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Players Test');
        //$suite->addTest(Controllers_AllTests::suite());
        $suite->addTest(Models_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
    AllTests::main();
}
