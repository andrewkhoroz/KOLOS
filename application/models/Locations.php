<?php

class Model_Locations extends Zend_Db_Table_Abstract {

    protected $_name = 'locations';
    protected $_dependentTables = array('Model_Clubs');


}