<?php

TestConfiguration::setup();

class TestConfiguration {

    static $appRoot;

    static function setup() {
        // Set your Zend Framework library path(s) here - default is the master lib/ directory
        $zfRoot = realpath(dirname(basename(__FILE__)) . '/../../lib/');
        $appRoot = realpath(dirname(basename(__FILE__)) . '/..');

        TestConfiguration::$appRoot = $appRoot;

        require_once 'PHPUnit/Framework.php';
        require_once 'PHPUnit/Framework/TestSuite.php';
        require_once 'PHPUnit/TextUI/TestRunner.php';

        error_reporting(E_ALL | E_STRICT);

        set_include_path($appRoot . '/application/models/'
                . PATH_SEPARATOR . $appRoot . '/library/'
                . PATH_SEPARATOR . $zfRoot
                . PATH_SEPARATOR . $zfRoot . '/incubator'
                . PATH_SEPARATOR . get_include_path());

        include 'Zend/Loader.php';
        Zend_Loader::registerAutoload();

        // load configuration
        $section = 'test';
        $config = new Zend_Config_Ini($appRoot . '/application/configuration/config.ini', $section);
        Zend_Registry::set('config', $config);

        // set up database
        $db = Zend_Db::factory($config->db);
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);
    }

    static function setupDatabase() {
        $db = Zend_Registry::get('db'); /* @var $db Zend_Db_Adapter_Abstract */

        $db->query(<<<EOT
DROP TABLE IF EXISTS players;
EOT
        );

        $db->query(<<<EOT
CREATE TABLE players (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
firstName VARCHAR( 256 ) NOT NULL ,
lastName VARCHAR( 256 ) NOT NULL ,
birthday DATETIME NOT NULL
)
EOT
        );

        $db->query(<<<EOT
INSERT INTO players (firstName, lastName, birthday)
VALUES 
('London', 'Regent''s Park', '2007-02-14 00:00:00')
,('Alton', 'Staffordshire', '2007-02-20 00:00:00')
,('Alcester', 'Warwickshire','2007-02-16 00:00:00')
;
EOT
        );
    }

}
