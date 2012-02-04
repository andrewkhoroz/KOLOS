<?php

/**
 * Description of Competition
 *
 * @author KHOROZ
 */
class Model_Competition extends Zend_Db_Table_Row_Abstract implements App_IGalerryable {

    protected $_primary = 'id';
    protected $clubsModel;
    protected $competitionsModel;

    public function init() {
        $this->clubsModel = new Model_Clubs();
        $this->competitionsModel = new Model_Competitions();
    }

    /**
     * Gets full name of competition (with period).
     * @return string 
     */
    public function getFullName() {
        return $this->name . ' (' . $this->getPeriod() . ')';
    }

    /**
     * Gets competition period.
     * 
     * @return string 
     */
    public function getPeriod() {
        $startPeriod = date("Y", strtotime($this->start_date));
        $period = $startPeriod;
        if (!empty($this->finish_date)) {
            $endPeriod = date("Y", strtotime($this->finish_date));
            if ($endPeriod > $startPeriod) {
                $period.='-' . $endPeriod;
            }
        }
        return $period;
    }

    /**
     * Повертає всі тури змагання
     * 
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getAllToursOfCompetition() {
        $tours = $this->findDependentRowset('Model_Tours', 'Competition');
        return $tours;
    }

    /**
     * Gets all clubs that take part in current competition.
     * 
     * @param array $except             Array of competition's ids to be excluded.
     * @return array                    Array of {@link Model_Club} instances.
     */
    public function getAllClubsOfCompetition($except=array()) {
        $clubs = $this->findManyToManyRowset('Model_Clubs', 'Model_CompetitionsClubs');
        $res = array();
        foreach ($clubs as $club) {
            if (!in_array($club->id, $except)) {
                $res[] = $club;
            }
        }
        return $res;
    }

    /**
     * Associate competition with clubs or remove clubs from competition.
     * 
     * @param array $clubIds            Array of clubs need to be associated.
     *                                  Before associating all records from `competitions_clubs`
     *                                  for current competition are deleted.
     * @return int                      If of current competition. 
     */
    public function associateCompetition($clubIds) {
        try {
            //remove previous relations
            $competitionClubModel = new Model_CompetitionsClubs();
            $where = $competitionClubModel->getAdapter()->quoteInto('competition_id = ?', $this->id);
            $competitionClubModel->delete($where);
            foreach ($clubIds as $clubId) {
                $competitionClubModel->insert(array("competition_id" => $this->id,
                    "club_id" => $clubId));
            }
            return $this->id;
        } catch (Exception $ex) {
            throw new Zend_Exception("Associate function failed");
        }
    }

    public function getResultsTable() {
        $cache = Zend_Registry::get('cache');

        if (($res = $cache->load('RESULT_TABLE_' . $this->id)) === false) {

            $tours = $this->getAllToursOfCompetition();
            $clubsThatPlayed = array();
            $res = array();

            foreach ($tours as $tour) {
                $matches = $tour->findAllMatches();
//                Zend_Debug::fdump(count($matches), '$matches');
                foreach ($matches as $match) {
                    if ($match->isFinished()) {
                        $owner = $this->clubsModel->find($match->owner_club_id)->current();
                        $guest = $this->clubsModel->find($match->guest_club_id)->current();

                        $clubsThatPlayed[$owner->id] = $owner;
                        $clubsThatPlayed[$guest->id] = $guest;

                        //set clubs name
                        if (empty($res[$owner->id]['club_obj'])) {
                            $res[$owner->id]['club_obj'] = $owner;
                        }
                        if (empty($res[$guest->id]['club_obj'])) {
                            $res[$guest->id]['club_obj'] = $guest;
                        }
                        //sets games count
                        $res[$owner->id]['games_count'] = (empty($res[$owner->id]['games_count']) ? 1 : ($res[$owner->id]['games_count'] + 1));
                        $res[$guest->id]['games_count'] = (empty($res[$guest->id]['games_count']) ? 1 : ($res[$guest->id]['games_count'] + 1));

                        $score = explode(':', $match->score);

                        //-----------sets wins count--------
                        //owner wins. set wins count +1 and points count +3
                        if ($score[0] > $score[1]) {
                            $res[$owner->id]['wins_count'] = (empty($res[$owner->id]['wins_count']) ? 1 : ($res[$owner->id]['wins_count'] + 1));
                            $res[$guest->id]['def_count'] = (empty($res[$guest->id]['def_count']) ? 1 : ($res[$guest->id]['def_count'] + 1));
                            $res[$owner->id]['points_count'] = (empty($res[$owner->id]['points_count']) ? 3 : ($res[$owner->id]['points_count'] + 3));
                        }
                        //nich. set nich count +1 and points count +1 for both clubs
                        if ($score[0] == $score[1]) {
                            $res[$owner->id]['nich_count'] = (empty($res[$owner->id]['nich_count']) ? 1 : ($res[$owner->id]['nich_count'] + 1));
                            $res[$owner->id]['points_count'] = (empty($res[$owner->id]['points_count']) ? 1 : ($res[$owner->id]['points_count'] + 1));
                            $res[$guest->id]['nich_count'] = (empty($res[$guest->id]['nich_count']) ? 1 : ($res[$guest->id]['nich_count'] + 1));
                            $res[$guest->id]['points_count'] = (empty($res[$guest->id]['points_count']) ? 1 : ($res[$guest->id]['points_count'] + 1));
                        }
                        //guest wins. set wins count +1 and points count +3
                        if ($score[0] < $score[1]) {
                            $res[$guest->id]['wins_count'] = (empty($res[$guest->id]['wins_count']) ? 1 : ($res[$guest->id]['wins_count'] + 1));
                            $res[$owner->id]['def_count'] = (empty($res[$owner->id]['def_count']) ? 1 : ($res[$owner->id]['def_count'] + 1));
                            $res[$guest->id]['points_count'] = (empty($res[$guest->id]['points_count']) ? 3 : ($res[$guest->id]['points_count'] + 3));
                        }

                        //sets balls balance
                        $res[$owner->id]['balls_balance_plus'] = (empty($res[$owner->id]['balls_balance_plus']) ? $score[0] : ($res[$owner->id]['balls_balance_plus'] + $score[0]));
                        $res[$guest->id]['balls_balance_plus'] = (empty($res[$guest->id]['balls_balance_plus']) ? $score[1] : ($res[$guest->id]['balls_balance_plus'] + $score[1]));
                        $res[$owner->id]['balls_balance_minus'] = (empty($res[$owner->id]['balls_balance_minus']) ? $score[1] : ($res[$owner->id]['balls_balance_minus'] + $score[1]));
                        $res[$guest->id]['balls_balance_minus'] = (empty($res[$guest->id]['balls_balance_minus']) ? $score[0] : ($res[$guest->id]['balls_balance_minus'] + $score[0]));
                    }
                }
            }
            $allCompetitionlubs = $this->getAllClubsOfCompetition();
            foreach ($allCompetitionlubs as $compClub) {
                if (!in_array($compClub, $clubsThatPlayed)) {
                    $res[$compClub->id]['club_obj'] = $compClub;
                    $res[$compClub->id]['balls_balance_plus'] = 0;
                    $res[$compClub->id]['balls_balance_minus'] = 0;
                    $res[$compClub->id]['points_count'] = 0;
                    $res[$compClub->id]['wins_count'] = 0;
                    $res[$compClub->id]['def_count'] = 0;
                    $res[$compClub->id]['nich_count'] = 0;
                    $res[$compClub->id]['games_count'] = 0;
                }
            }
//            $cache->save($res, 'RESULT_TABLE_' . $this->id);
        }
        require_once 'tools.php';
        usort(&$res, cmpClubRes);
        return $res;
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

    public function getNearestTours() {
        $toursModel = new Model_Tours();
        return $toursModel->getNearestToursOfCompetition($this);
    }

    public function getRelatedCompetition() {
        $res = false;
        if (!empty($this->related_competition_id)) {
            $res = $this->competitionsModel->find($this->related_competition_id)->current();
        }
        return $res;
    }

}

?>
