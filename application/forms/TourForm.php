<?php

/**
 * Description of Form_TourForm
 *
 * @author KHOROZ
 */
class Form_TourForm extends ZendX_JQuery_Form {

    public function __construct(Model_Competition $competition) {

        $id = $this->createElement("hidden", "tour_id");
        $competitionId = $this->createElement("hidden", "competition_id");
        $competitionId->setValue($competition->id);

        $tourDate = $this->createElement("text", "tour_date");
        $tourDate->setLabel("Дата проведення:");
        $tourDate->setAttrib("style", "display:inline");
        $tourDate->setRequired(true);
        $tourDate->addValidators(array(
            new Zend_Validate_Date("YYYY/MM/DD")
        ));

        $tourName = $this->createElement('text', 'name');
        $tourName->setRequired(true)
                 ->setLabel('Назва: ');


        $submit = $this->createElement("submit", "Add");

        $this->addElements(array($id, $tourName, $competitionId, $tourDate));
        $this->addAttribs(array('tour-form'));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl')),
            'Form'
        ));
//        die();
    }

    public function prefill(Model_Tour $tour) {
        $this->populate($tour->toArray());
        $tourDate = date("Y/m/d", strtotime($tour->tour_date));
        $this->getElement('plan_date')->setValue($tourDate);
    }

}

?>
