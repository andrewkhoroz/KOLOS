<?php

/**
 * Description of Form_ClubForm
 *
 * @author KHOROZ
 */
class Form_ClubForm extends ZendX_JQuery_Form {

    public function init() {
        $id = $this->createElement("hidden", "club_id");
        $galleryId = $this->createElement("hidden", "gallery_id");

        $name = $this->createElement("text", "name");
        $name->setLabel("Name:");
        $name->setRequired(true);

        $treiner = $this->createElement("text", "treiner");
        $treiner->setLabel("Treiner:");
        $treiner->setRequired(true);

//        $logo = $this->createElement("text", "logo");
//        $logo->setLabel("Logo:");

        $description = $this->createElement("textarea", "description");
        $description->setLabel("Description:");
        $description->setAttrib("cols", 80);
        $description->setAttrib("rows", 6);
        $description->setRequired(true);

        $location = $this->createElement("text", "location");
        $location->setLabel("Location:");
        $location->setRequired(true);


        $submit = $this->createElement("submit", "Add");

        $this->addElements(array($id, $galleryId, $name, $location, $treiner, $description, $galleryId));
        $this->addAttribs(array('club-form'));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl')),
            'Form'
        ));
    }

    public function prefill($club) {
        $this->populate($club->toArray());
        $this->getElement('club_id')->setValue($club->id);
    }

}

?>
