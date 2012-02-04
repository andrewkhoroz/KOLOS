<?php

/**
 * Description of Model_Tour
 *
 * @author KHOROZ
 */
class Model_Tour extends Zend_Db_Table_Row_Abstract {

    protected $_primary = 'id';

    /**
     * Повертає всі матчі туру змагання
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function findAllMatches() {
        $matches = $this->findDependentRowset('Model_Matches', 'Tour');
        return $matches;
    }

    public function fullName() {
        return $this->name . ' <span style="color:#757575">(' . date("d/m/Y", strtotime($this->tour_date)) . ')</span>';
    }

}

?>
