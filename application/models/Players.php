<?php

/**
 * Description of Players
 *
 * @author KHOROZ
 */
class Model_Players extends Zend_Db_Table_Abstract {

    protected $_name = 'players';
    protected $_referenceMap = array(
        'Model_PlayerPositions' => array(
            'columns' => array('position_id'),
            'refTableClass' => 'Model_Positions',
            'refColumns' => array('id')
        )
    );


    protected $_dependentTables = array('Model_ClubsPlayers');
}
?>
