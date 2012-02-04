<?php

/**
 * Description of CompetitionsClubsForm
 *
 * @author KHOROZ
 */
class Form_CompetitionsClubsForm extends ZendX_JQuery_Form {

    public $clubCheckboxPrefix = "club_";

    public function __construct($allClubs) {
        $this->clearDecorators();

        $competitionId = $this->createElement("hidden", "competition_id");
        $this->addElements(array($competitionId));

        foreach ($allClubs as $club) {
            $chechBox = $this->createElement("checkbox", $this->clubCheckboxPrefix . $club->id);
            $chechBox->setLabel($club->name);
            $chechBox->setValue($club->id);
            $chechBox->setCheckedValue($club->id);
            $chechBox->setChecked(false);
            $this->addElement($chechBox);
        }

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl')),
            'Form'
        ));
    }

    public function prefill(Model_Competition $competition) {
        $this->getElement("competition_id")->setValue($competition->id);
        $checkedClubs = $competition->findManyToManyRowset('Model_Clubs', 'Model_CompetitionsClubs');
        $checkedIds = array();
        foreach ($checkedClubs as $checkedClub) {
            $checkedIds[] = $checkedClub->id;
        }


        foreach ($this->getElements() as $name => $elem) {
            if (strpos($name, $this->clubCheckboxPrefix) !== false) {//needed checkbox
                if (in_array($elem->getCheckedValue(), $checkedIds)) {//prefill checked club
                    $elem->setChecked(true);
                } else {
                    $elem->setChecked(false);
                }
            }
        }
    }

}
?>