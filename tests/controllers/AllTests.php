<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Controllers_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once dirname(__FILE__) . '/IndexControllerTest.php';

class Controllers_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('ZFiA Places - Controller Tests');

        $suite->addTestSuite('controllers_IndexControllerTest');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Controllers_AllTests::main') {
    Controllers_AllTests::main();
}
