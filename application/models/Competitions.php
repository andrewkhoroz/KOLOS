<?php

/**
 * Description of Competitions
 *
 * @author KHOROZ
 */
class Model_Competitions extends Model_Abstract implements Zend_Acl_Resource_Interface {

    protected $_primary = 'id';
    protected $_name = 'competitions';
    protected $_rowClass = 'Model_Competition';
    protected $_dependentTables = array('Model_Tours');
    protected $_referenceMap = array(
        'Gallery' => array(
            'columns' => array('gallery_id'),
            'refTableClass' => 'Model_Galleries',
            'refColumns' => array('id')),
    );

    public function getResourceId() {
        return 'competition';
    }

    //select all competitions
    public function fetchCompetitions() {
        $select = $this->select();
        return parent::fetchAll($select);
    }

    private function _createNewCompetition($name, $startDate, $finishDate, $logo, $description, $galleryId) {
        $comp = $this->createRow(); //new Model_Competition();
        $comp->name = $name;
        $comp->start_date = $startDate;
        $comp->finish_date = $finishDate;
        $comp->logo = $logo;
        $comp->description = $description;
        $comp->gallery_id = $galleryId;

        $comp->save();

        return $comp;
    }

    private function _updateCompetition($competitionId, $name, $startDate, $finishDate, $logo, $description, $galleryId) {
        $comp = $this->find($competitionId)->current();
        if ($comp) {
            try {
                $comp->name = $name;
                $comp->start_date = date("c", strtotime($startDate));
                $comp->finish_date = date("c", strtotime($finishDate));
                $comp->logo = $logo;
                $comp->description = $description;
                $comp->gallery_id = $galleryId;
                $comp->save();
                return $comp;
            } catch (Exception $ex) {
                throw new Zend_Exception("Update function failed");
            }
        } else {
            throw new Zend_Exception("Update function failed; coud not find row !");
        }
    }

    public function createUpdate($competitionId, $name, $startDate, $finishDate, $logo, $description, $galleryId) {
        $comp = null;
        if (!empty($competitionId)) {
            $comp = $this->_updateCompetition($competitionId, $name, $startDate, $finishDate, $logo, $description, $galleryId);
        } else {
            $comp = $this->_createNewCompetition($name, $startDate, $finishDate, $logo, $description, $galleryId);
        }
        return $comp;
    }

}

?>
