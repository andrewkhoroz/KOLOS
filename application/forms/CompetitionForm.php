<?php

/**
 * Description of CompetitionForm
 *
 * @author KHOROZ
 */
class Form_CompetitionForm extends ZendX_JQuery_Form {

    public function init() {
        $elementDecorators = array(
            array('ViewHelper'),
            array('Errors'),
            array('Label', array(
                    'requiredSuffix' => ' *',
                    'class' => 'leftalign',
                    'tag' => 'td'
            )), array('HtmlTag', array(
                    'tag' => 'td'
            )),
            array('HtmlTag', array('tag' => 'tr')),
        );


        $id = $this->createElement("hidden", "competition_id");
        $galleryId = $this->createElement("hidden", "gallery_id");

        $name = $this->createElement("text", "name");
        $name->setLabel("Name:");
        $name->setRequired(true);


        $stratDate = $this->createElement("text", "start_date");
        $stratDate->setLabel("Дата початку:");
        $stratDate->setRequired(true);
        $stratDate->addValidators(array(
            new Zend_Validate_Date("YYYY/MM/DD")
        ));

        $finishDate = $this->createElement("text", "finish_date");
        $finishDate->setLabel("Дата завершення:");
//        $finishDate->setRequired(true);
        $finishDate->addValidators(array(
            new Zend_Validate_Date("YYYY/MM/DD")
        ));

        $logo = $this->createElement("text", "logo");
        $logo->setLabel("Logo:");

        $description = $this->createElement("textarea", "description");
        $description->setLabel("Description:");
        $description->setAttrib("cols", 73);
        $description->setAttrib("rows", 8);
        $description->setRequired(true);

        $submit = $this->createElement("submit", "Add");

        $allElements = array($id,$galleryId, $name, $stratDate, $finishDate, $logo, $description,$submit);
        foreach ($allElements as $el) {
//            $el->setDecorators($elementDecorators);
        }
        $this->addElements($allElements);
        $this->addAttribs(array('competition-form'));

        $this->clearDecorators();
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl',
                    'class' => 'comp_input_data')),
            'Form'
        ));
    }

    public function prefill(Model_Competition $competiton) {
        $this->populate($competiton->toArray());
        $startDate = date("Y/m/d", strtotime($competiton->start_date));
        $finishDate = date("Y/m/d", strtotime($competiton->finish_date));
        $this->getElement('start_date')->setValue($startDate);

        $buf = intval($competiton->finish_date);
        if (!empty($buf)) {
            $this->getElement('finish_date')->setValue($finishDate);
        }
        $this->getElement('competition_id')->setValue($competiton->id);
    }

}

?>
