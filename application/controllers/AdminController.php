<?php

/**
 * Description of AdminController
 *
 * @author KHOROZ
 */
class AdminController extends Zend_Controller_Action {

    /**
     * This action is the home page of the website
     *
     */
    public function indexAction() {
        $this->view->title = 'Адмін';
        $this->view->headTitle('Адмін');
    }

}
?>
