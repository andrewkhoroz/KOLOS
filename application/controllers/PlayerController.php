<?php

/**
 * Description of PlayersController
 *
 * @author KHOROZ
 */
class PlayerController extends Zend_Controller_Action {

    /**
     * The controller's init() function is called before
     * the action. Usually we use it to set up the ACL
     * restrictions for the actions within the controller.
     *
     */
    public function init() {

    }

    /**
     * This action is the home page of the website
     *
     */
    public function indexAction() {
        $this->view->title = 'Список гравців';
        $this->view->headTitle('Players');
    }

}
?>
