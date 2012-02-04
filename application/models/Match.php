<?php

/**
 * Description of Model_Match
 *
 * @author KHOROZ
 */
class Model_Match extends Zend_Db_Table_Row_Abstract implements App_IGalerryable {

    protected $_primary = 'id';

    public function getMatchTitle() {
        $ownerClub = $this->getOwnerClub();
        $guestClub = $this->getGuestClub();

        return $ownerClub->name . ' ' . $this->getMatchScore() . ' ' . $guestClub->name;
    }

    public function getOwnerClub() {
        $clubsModel = new Model_Clubs();
        return $clubsModel->find($this->owner_club_id)->current();
    }

    public function getGuestClub() {
        $clubsModel = new Model_Clubs();
        return $clubsModel->find($this->guest_club_id)->current();
    }

    public function getMatchScore() {
        if (is_null($this->score) || empty($this->score)) {
            return ' _:_ ';
        } else {
            return '<a href="/match/view/id/' . $this->id . '">' . $this->score . '</a>';
        }
    }

    public function isFinished() {
        return ($this->match_status_id == 2 ? true : false);
    }

    /**
     *
     * @return Model_Gallery 
     */
    public function getGallery() {
        $galleriesModel = new Model_Galleries();
        $gallery = $galleriesModel->find($this->gallery_id)->current();
        return $gallery;
    }

}

?>
