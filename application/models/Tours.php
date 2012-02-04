<?php

/**
 * Description of Model_Tours
 *
 * @author ANDREW
 */
class Model_Tours extends Model_Abstract {

    protected $_name = 'tours';
    protected $_rowClass = 'Model_Tour';
    protected $_dependentTables = array('Model_Matches');
    protected $_referenceMap = array(
        'Competition' => array(
            'columns' => array('competition_id'),
            'refTableClass' => 'Model_Competitions',
            'refColumns' => array('id')));

    public function getNearestToursOfCompetition(Model_Competition $competition=null) {
        $select = $this->select();
        $select->order('tour_date ASC');
        if (!empty($competition)) {
            $select->where('competition_id=?', $competition->id);
        }
        $currentTime = time();
        $tours = parent::fetchAll($select);
        $toursToShow = array();

        if (count($tours) > 3) {
            $futureTours = array();
            $pastTours = array();
            for ($k = 0; $k < count($tours); $k++) {
                if (strtotime($tours[$k]->tour_date) >= $currentTime) {
                    $futureTours[] = $tours[$k];
                } else {
                    $pastTours[] = $tours[$k];
                }
            }
            $futureToutsCount = count($futureTours);
            $pastToursCount = count($pastTours);
            for ($i = 0; $i < max(1, (3 - count($futureTours))); $i++) {
                $toursToShow[] = $pastTours[$pastToursCount - $i - 1];
            }
            $toursToShow = array_reverse($toursToShow);
            $toursToShowCount = count($toursToShow);
            for ($i = 0; $i < (3 - $toursToShowCount); $i++) {
                $toursToShow[] = $futureTours[$i];
            }
        } else {
            $toursToShow = $tours;
        }
        return $toursToShow;
    }

    private function createNewTour($name, $competitionId, $tourDate) {
        $tour = $this->createRow(); //new Model_Competition();
        $tour->name = $name;
        $tour->competition_id = $competitionId;
        $tour->tour_date = $tourDate;
        $tour->save();

        return intval($tour->id);
    }

    private function updateTour($tourId, $name, $competitionId, $tourDate) {
        $tour = $this->find($tourId)->current();
        if ($tour) {
            try {
                $tour->name = $name;
                $tour->competition_id = $competitionId;
                $tour->tour_date = date("c", strtotime($tourDate));
                $tour->save();
                return intval($tour->id);
            } catch (Exception $ex) {
                throw new Zend_Exception("Update function failed");
            }
        } else {
            throw new Zend_Exception("Update function failed; coud not find row !");
        }
    }

    public function createUpdate($tourId, $name, $competitionId, $tourDate) {
        $savedId = 0;
        if (!empty($tourId)) {
            $savedId = $this->updateTour($tourId, $name, $competitionId, $tourDate);
        } else {
            $savedId = $this->createNewTour($name, $competitionId, $tourDate);
        }
        return $savedId;
    }

}

?>
