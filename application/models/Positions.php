<?php

/**
 * Description of Positions
 *
 * @author KHOROZ
 */
class Model_Positions extends Zend_Db_Table_Abstract {

    protected $_name = 'player_positions';
    protected $_dependentTables = array('Model_Players');

}
?>
