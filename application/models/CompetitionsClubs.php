<?php

/**
 * Description of CompetitionsClubs
 *
 * @author KHOROZ
 */
class Model_CompetitionsClubs extends Zend_Db_Table_Abstract {

    //table name
    protected $_name = 'competitions_clubs';
    protected $_primary = array('competition_id','club_id');
    protected $_referenceMap = array(
        'Model_Competition' => array(
            'columns' => array('competition_id'),
            'refTableClass' => 'Model_Competitions',
            'refColumns' => array('id')),
        'Model_Club' => array(
            'columns' => array('club_id'),
            'refTableClass' => 'Model_Clubs',
            'refColumns' => array('id'))
    );

}
?>
