<?php

/**
 * Description of TourController
 *
 * @author KHOROZ
 */
class TourController extends App_AbstractController {

    protected $toursModel;
    protected $competitionsModel;

    public function init() {
        // AJAX
        $this->_modelName = 'tour';
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $this->_helper->ajaxContext->setAutoJsonSerialization(false)
                ->addActionContext('view', 'html')
                ->addActionContext('index', 'html')
                ->addActionContext('manage', array('html'))
                ->initContext();
    }

    public function viewAction() {
//        $this->view->headTitle('Tours');
        $id = (int) $this->getRequest()->getParam('tour_id');
//        Zend_Debug::fdump($id,'$id');
        if ($id == 0) {
            throw new Exception('Unknown tour');
            return;
        }

        $tour = $this->toursModel->find($id)->current();
        $this->view->tour = $tour;
        $this->view->title = $tour->name;
    }

    public function preDispatch() {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/match/match.js');
        $this->toursModel = new Model_Tours();
        $this->competitionsModel = new Model_Competitions();
    }

    public function createUpdateAction() {

        $this->_helper->viewRenderer->setNoRender(true);
        $compId = $this->_request->getParam("comp_id");
        $competition = $this->competitionsModel->find($compId)->current();
        $tourForm = new Form_TourForm($competition);
        $tourForm->setMethod('POST');
        $data = array();

        if ($this->getRequest()->isPost()) {
            if ($tourForm->isValid($_POST)) {
                $result = $this->toursModel->createUpdate(
                                $tourForm->getValue("tour_id"), $tourForm->getValue("name"), $tourForm->getValue("competition_id"), $tourForm->getValue("tour_date"));
                $data['tour_id'] = $result;
            } else {
                $data['errors'] = $tourForm->getMessages();
            }
        } else {
            $id = $this->_request->getParam("id");
            if (!empty($id)) {
                $tour = $this->tourModel->find($id)->current();
                $tourForm->prefill($tour);
                $this->view->tour = $tour;
            }
        }
        $this->view->form = $tourForm;
        $content = $this->view->render('tour/create-update.json.phtml');
        $data['content'] = $content;
        $this->_helper->json($data);
    }

    /**
     * This action is the home page of the website
     *
     */
    public function manageAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/jQuery/nicEdit.js');

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/jQuery/jquery.spinbutton.css');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/jQuery/jquery.maskedinput.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/jQuery/jquery.spinbutton.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.fileupload.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.fileupload-ui.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.iframe-transport.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/modules/upload/jquery.tmpl.min.js');

        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/modules/upload/jquery.fileupload-ui.css');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/modules/upload/style.css');

        $compId = (int) $this->getRequest()->getParam('comp_id');
        $competition = new Model_Competition();
        $competition = $this->competitionsModel->find($compId)->current();
        if (empty($competition)) {
            throw new Model_Exception_ModelNotFound('Competition with id ' . $compId . ' not found');
        }

        $this->view->title = $competition->name . ' Усі матчі туру (Admin part) ';
        $this->view->headTitle('Тур');

        $adapter = $this->toursModel->fetchPaginatorAdapter(array('competition_id'=>$competition->id));
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(5);
        $page = $this->getRequest()->getParam('page', 1);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
        $this->view->competition = $competition;
    }

    public function indexAction() {
        
    }

}

?>
