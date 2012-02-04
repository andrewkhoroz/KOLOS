<?php

/**
 * Description of TagController
 *
 * @author KHOROZ
 */
class TagController extends Zend_Controller_Action {

    /**
     * The controller's init() function is called before
     * the action. Usually we use it to set up the ACL
     * restrictions for the actions within the controller.
     *
     */
    public function init() {
        
    }

    public function preDispatch() {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/jQuery/jquery.autocomplete.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/jQuery/jquery.tagit.js');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/tag/tagit-gradient-blue.css');
    }

    /**
     * This action is the home page of the website
     *
     */
    public function indexAction() {
        $this->view->title = 'TagController';
        $this->view->headTitle('TagController');
    }

}

?>
