<?php

/**
 * Description of Competitions
 *
 * @author KHOROZ
 */
class Model_Matches extends Model_Abstract {

    protected $_name = 'matches';
    protected $_rowClass = 'Model_Match';
    protected $_referenceMap = array(
        'Competition' => array(
            'columns' => array('competition_id'),
            'refTableClass' => 'Model_Competitions',
            'refColumns' => array('id')),
        'OwnerClub' => array(
            'columns' => array('owner_club_id'),
            'refTableClass' => 'Model_Clubs',
            'refColumns' => array('id')),
        'GuestClub' => array(
            'columns' => array('guest_club_id'),
            'refTableClass' => 'Model_Clubs',
            'refColumns' => array('id')),
        'Tour' => array(
            'columns' => array('tour_id'),
            'refTableClass' => 'Model_Tours',
            'refColumns' => array('id')),
        'Gallery' => array(
            'columns' => array('gallery_id'),
            'refTableClass' => 'Model_Galleries',
            'refColumns' => array('id')),
    );

    /**
     * Finds match by id
     * 
     * @param type $id
     * @return Model_Match
     */
    public function find($id) {
        return parent::find($id);
    }

    private function _createNewMatch($date, $ownerClubId, $guestClubId, $score, $tourId, $statusId, $description, $galleryId) {
        $math = $this->createRow();
        $math->date = $date;
        $math->owner_club_id = $ownerClubId;
        $math->guest_club_id = $guestClubId;
        $math->score = $score;
        $math->tour_id = $tourId;
        $math->match_status_id = $statusId;
        $math->description = $description;
        $math->gallery_id = $galleryId;

        $math->save();

        return $math;
    }

    private function _updateMatch($matchId, $date, $ownerClubId, $guestClubId, $score, $tourId, $statusId, $description, $galleryId) {
        $math = $this->find($matchId)->current();
        if ($math) {
            try {

                $math->date = $date;
                $math->owner_club_id = $ownerClubId;
                $math->guest_club_id = $guestClubId;
                $math->score = $score;
                $math->tour_id = $tourId;
                $math->match_status_id = $statusId;
                $math->description = $description;
                $math->gallery_id = $galleryId;

                $math->save();

                return $math;
            } catch (Exception $ex) {
                throw new Zend_Exception("Update function failed");
            }
        } else {
            throw new Zend_Exception("Update function failed; coud not find row !");
        }
    }

    public function createUpdate($matchId, $date, $ownerClubId, $guestClubId, $score, $tourId, $statusId, $description, $galleryId) {
        $match = 0;
        if (!empty($matchId)) {
            $match = $this->_updateMatch($matchId, $date, $ownerClubId, $guestClubId, $score, $tourId, $statusId, $description, $galleryId);
        } else {
            $match = $this->_createNewMatch($date, $ownerClubId, $guestClubId, $score, $tourId, $statusId, $description, $galleryId);
        }
        return $match;
    }

    public function getsByCompetition(Model_Competition $competition) {
        
    }

}

?>
