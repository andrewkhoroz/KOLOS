<?php

/**
 * Description of Clubs
 *
 * @author KHOROZ
 */
class Model_Clubs extends Model_Abstract {

    protected $_name = 'clubs';
    protected $_rowClass = 'Model_Club';
    protected $_dependentTables = array('Model_ClubsPlayers', 'Model_Matches', 'Model_CompetitionsClubs');
    protected $_referenceMap = array(
        'Gallery' => array(
            'columns' => array('gallery_id'),
            'refTableClass' => 'Model_Galleries',
            'refColumns' => array('id')),
    );

    public function fetchClubs() {
        $select = $this->select();
        $select->order('name ASC');
        return parent::fetchAll($select);
    }

    private function _createNewClub($name, $location, $treiner, $description, $galleryId) {
        $club = $this->createRow();
        $club->name = $name;
        $club->location = $location;
        $club->treiner = $treiner;
        $club->description = $description;
        $club->gallery_id = $galleryId;
        $club->save();

        return intval($club->id);
    }

    private function _updateClub($clubId, $name, $location, $treiner, $description, $galleryId) {
        $club = $this->find($clubId)->current();

        if ($club) {
            try {
                $club->name = $name;
                $club->location = $location;
                $club->treiner = $treiner;
                $club->description = $description;
                $club->gallery_id = $galleryId;
                $club->save();
                return intval($club->id);
            } catch (Exception $ex) {
                Zend_Debug::fdump($ex, '$ex');
                throw new Zend_Exception("Update function failed $ex");
            }
        } else {
            throw new Zend_Exception("Update function failed; coud not find row !");
        }
    }


    public function createUpdate($clubId, $name, $location, $treiner, $description, $galleryId) {
        $savedId = 0;
        if (!empty($clubId)) {
            $savedId = $this->_updateClub($clubId, $name, $location, $treiner, $description, $galleryId);
        } else {
            $savedId = $this->_createNewClub($name, $location, $treiner, $description, $galleryId);
        }
        return $savedId;
    }

}

?>
