<?php

/**
 * Description of ClubPlayers
 *
 * @author KHOROZ
 */
class Model_ClubsPlayers extends Zend_Db_Table_Abstract {

    protected $_name = 'clubs_players';
    protected $_referenceMap = array(
        'Players' => array(
            'columns' => array('id'),
            'refTableClass' => 'Players',
            'refColumns' => 'player_id'),
        'Clubs' => array(
            'columns' => array('id'),
            'refTableClass' => 'Clubs',
            'refColumns' => 'club_id')
    );

}
?>
