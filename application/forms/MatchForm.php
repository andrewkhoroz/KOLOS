<?php

/**
 * Description of MatchForm
 *
 * @author KHOROZ
 */
class Form_MatchForm extends ZendX_JQuery_Form {

    public function __construct(Model_Competition $competition, Model_Tour $selectedTour) {

        $allClubs = $competition->getAllClubsOfCompetition();
        $allTours = $competition->getAllToursOfCompetition();

        $id = $this->createElement("hidden", "match_id");
        $galleryId = $this->createElement("hidden", "gallery_id");

        $matchDate = $this->createElement("text", "match_date");
        $matchDate->setLabel("Дата проведення матчу:");
        $matchDate->setAttrib("style", "display:inline");
        $matchDate->setRequired(true);
        $matchDate->addValidators(array(
            new Zend_Validate_Date("YYYY/MM/DD")
        ));

        $ownerClubSelect = $this->createElement("select", "owner_club_id");
        $ownerClubSelect->setLabel("Господарі");
        $ownerClubSelect->setRequired(true);
        foreach ($allClubs as $club) {
            $ownerClubSelect->addMultiOption($club->id, $club->name);
        }
        $ownerClubSelect->setAttribs(array('class' => 'owner-select'));

        $ownerScore = $this->createElement("text", "owner_score");
        $ownerScore->setAttribs(array("max" => 9, "size" => 3, "id" => "spinbox", "value" => 0));
        $ownerScore->setAttribs(array('class' => 'owner-score'));


        $guestClubSelect = $this->createElement("select", "guest_club_id");
        $guestClubSelect->setLabel("Гості");
        $guestClubSelect->setRequired(true);
        foreach ($allClubs as $club) {
            $guestClubSelect->addMultiOption($club->id, $club->name);
        }
        $guestClubSelect->setAttribs(array('class' => 'guest-select'));


        $guestScore = $this->createElement("text", "guest_score");
        $guestScore->setAttribs(array("max" => 9, "size" => 3, "id" => "spinbox", "value" => 0));
        $guestScore->setAttribs(array('class' => 'guest-score'));



        $tourSelect = $this->createElement("select", "tour_id");
        $tourSelect->setLabel("Тур");
        $tourSelect->setRequired(true);
        foreach ($allTours as $tour) {
            $tourSelect->addMultiOption($tour->id, $tour->name);
        }
        $tourSelect->setAttribs(array('class' => 'tour-select'));

        $matchStatusSelect = $this->createElement("select", "match_status_id");
        $matchStatusSelect->setLabel("Статус матчу");
        $matchStatusSelect->setRequired(true);
        $matchStatusSelect->addMultiOption(1, 'not started');
        $matchStatusSelect->addMultiOption(2, 'finished');


        $description = $this->createElement("textarea", "description");
        $description->setLabel("Description:");
        $description->setAttrib("cols", 90);
        $description->setAttrib("rows", 6);
        $description->setRequired(true);

        $submit = $this->createElement("submit", "Add");

        $this->addElements(array($id, $galleryId, $matchDate, $matchStatusSelect, $ownerClubSelect, $ownerScore,
            $guestClubSelect, $guestScore, $tourSelect, $description));
        $this->addAttribs(array('match-form'));

        $startMatchDate = date("Y/m/d", strtotime($selectedTour->tour_date));
        $this->getElement('match_date')->setValue($startMatchDate);
        $tourSelect->setValue($selectedTour->id);

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl')),
            'Form'
        ));
//        die();
    }

    public function prefill(Model_Match $match) {
//        Zend_Debug::fdump($match->toArray(),'ara');
        $this->populate($match->toArray());
        $ownerScore = '';
        $guestScore = '';
        if (!empty($match->score)) {
            $scores = explode(':', $match->score);
            $ownerScore = intval(trim($scores[0]));
            $guestScore = intval(trim($scores[1]));
        }

        $startDate = date("Y/m/d", strtotime($match->date));
        $this->getElement('match_date')->setValue($startDate);
        $this->getElement('match_id')->setValue($match->id);

        $this->getElement('owner_score')->setValue($ownerScore);
        $this->getElement('guest_score')->setValue($guestScore);
    }

}

?>
