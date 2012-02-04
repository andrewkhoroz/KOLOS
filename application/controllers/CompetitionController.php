<?php

/**
 * Description of CompetitionController
 *
 * @author KHOROZ
 */
class CompetitionController extends App_AbstractController {

    protected $competitionsModel;
    protected $clubsModel;
    protected $toursModel;

    public function init() {
        // AJAX
        $this->_modelName = 'competition';
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $this->_helper->ajaxContext->setAutoJsonSerialization(false)
                ->addActionContext('view', 'html')
                ->addActionContext('index', 'html')
                ->addActionContext('manage', array('html'))
                ->addActionContext('createUpdate', array('json'))
                ->addActionContext('toursViewer', array('html'))
                ->addActionContext('delete', 'html')
                ->addActionContext('associate', 'json')
                ->initContext();
    }

    public function preDispatch() {
        $this->competitionsModel = new Model_Competitions();
        $this->clubsModel = new Model_Clubs();
        $this->toursModel = new Model_Tours();
    }

    public function indexAction() {
        $this->view->title = 'Список змагань';
        $this->view->headTitle('Список змагань');
        parent::indexAction();
    }

    public function manageAction() {
        $this->view->title = 'Змагання (Admin part)';
        $this->view->headTitle('Змагання');
        parent::manageAction();
    }

    public function viewAction() {
        parent::viewAction();
        $id = (int) $this->getRequest()->getParam('id');
        if ($id == 0) {
            throw new Exception('Unknown competition');
            return;
        }

        $competition = $this->competitionsModel->find($id)->current();
        $this->view->headTitle($competition->getFullName());
        if ($competition->id != $id) {
            $this->_redirect('/' . $this->getRequest()->getControllerName() . '/');
            return;
        }
        $this->view->competition = $competition;
        $this->view->title = $competition->getFullName();
    }

    public function toursViewerAction() {
        if ($this->getRequest()->isPost()) {
            $this->view->isPost = true;
            $this->_helper->getHelper('layout')->disableLayout();
        }

        $select = $this->toursModel->select();
        $select->order('id ASC');

        $session = Model_Session::getSession();
        if (!empty($session->competiton_id)) {
            $comp = $this->competitionsModel->find($session->competiton_id)->current();
            $adapter = $this->toursModel->fetchPaginatorAdapter(array('competition_id' => $comp->id), $select);
            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage(1);
            $page = $this->getRequest()->getParam('page', $adapter->count());
            $paginator->setCurrentPageNumber($page);
            $this->view->paginator = $paginator;
            $this->view->pageNumber = $page;

            $this->view->{'toursCount'} = $adapter->count();
        }
    }

    public function calendarAction() {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id == 0) {
            throw new Exception('Unknown competition');
            return;
        }

        $competition = $this->competitionsModel->find($id)->current();
        $this->view->headTitle($competition->getFullName());
        if ($competition->id != $id) {
            $this->_redirect('/' . $this->getRequest()->getControllerName() . '/');
            return;
        }
        $this->view->competition = $competition;
        $this->view->title = $competition->getFullName();
    }

    /**
     * 
     */
    public function createUpdateAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $competitionForm = new Form_CompetitionForm();
        $competitionForm->setMethod('POST');
        $data = array();

        if ($this->getRequest()->isPost()) {
            if ($competitionForm->isValid($_POST)) {
                $result = $this->competitionsModel->createUpdate(
                                $competitionForm->getValue("competition_id"), $competitionForm->getValue("name"), $competitionForm->getValue("start_date"), $competitionForm->getValue("finish_date"), $competitionForm->getValue("logo"), $competitionForm->getValue("description"), $competitionForm->getValue("gallery_id")
                );
                $data['id'] = $result->id;
            } else {
                $data['errors'] = $competitionForm->getMessages();
            }
        } else {
            $id = $this->_request->getParam("id");
            if (!empty($id)) {
                $comp = $this->competitionsModel->find($id)->current();
                $competitionForm->prefill($comp);
                $this->view->competition = $comp;
            }
            $gallery = null;
            $sessionCustom = new Zend_Session_Namespace('custom');
            if (!empty($comp)) {
                if (!empty($comp->gallery_id)) {
                    $gallery = $comp->getGallery();
                } else {
                    $defGallery = new Model_Galleries();
                    $gallery = $defGallery->createGallery('competition', $id);
                    $comp->gallery_id = $gallery->id;
                    $comp->save();
                    $competitionForm->getElement('gallery_id')->setValue($gallery->id);
                }
            } else {

                $defGallery = new Model_Galleries();
                $gallery = $defGallery->createGallery('competition', $id);
                $competitionForm->getElement('gallery_id')->setValue($gallery->id);
            }
            $sessionCustom->galleryObject = $gallery;
        }
//        Zend_Debug::fdump($sessionCustom->galleryObject, '$sessionCustom->galleryObject');
        $this->view->form = $competitionForm;
        $content = $this->view->render('competition/create-update.json.phtml');
        $data['content'] = $content;
        $this->_helper->json($data);
    }

    public function confirmAction() {
        
    }

    /**
     * Р—РІСЏР·СѓС”(РІС–РґРІСЏР·СѓС”) Р·РјР°РіР°РЅРЅСЏ Р· РєР»СѓР±Р°РјРё
     */
    public function associateAction() {
        $this->_helper->viewRenderer->setNoRender(true);

        $allClubs = $this->clubsModel->fetchClubs(array());
        $compClubsForm = new Form_CompetitionsClubsForm($allClubs);

        $data = array();
        $associatedComp = 0;
        if ($this->getRequest()->isPost()) {
            if ($compClubsForm->isValid($_POST)) {
                $clubIds = array();
                foreach ($compClubsForm->getElements() as $id => $emt) {
                    if (strstr($id, $compClubsForm->clubCheckboxPrefix)) {
                        if ($emt->isChecked()) {
                            $clubIds[] = $emt->getCheckedValue();
                        }
                    }
                }
                $associatedComp = $this->competitionsModel->find($compClubsForm->getValue("competition_id"))->current();
                $result = $associatedComp->associateCompetition($clubIds);
                $data['competition_id'] = $result;
            } else {
                $data['errors'] = $compClubsForm->getMessages();
            }
        } else {
            $compId = $this->getRequest()->getParam("competition_id");
            $associatedComp = $this->competitionsModel->find($compId)->current();
            $compClubsForm->prefill($associatedComp);
            $this->view->form = $compClubsForm;
        }
        $this->view->competition = $associatedComp;
        $content = $this->view->render('competition/associate.json.phtml');
        $data['content'] = $content;
        $this->_helper->json($data);
    }

    /**
     * 
     */
    public function responsibilityAction() {
        $competitions = $this->competitionsModel->fetchCompetitions(array());

        $this->view->competitionsClubs = array();

        foreach ($competitions as $competition) {
            $tempArray = array();
            $tempArray['name'] = $competition->name;
            $tempArray['clubs'] = array();

            $clubs = $competition->findManyToManyRowset('Model_Clubs', 'Model_CompetitionsClubs');
            foreach ($clubs as $clubRow) {
                $tempArray['clubs'][] = $clubRow->name;
            }

            $this->view->competitionsClubs[] = $tempArray;
        }
    }

    /**
     * Validate form via AJAX
     */
    public function validateFormAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
        $form = new Form_CompetitionForm();
        $form->isValid($this->_getAllParams());
        $json = $form->getMessages();
        header('Content-type: application/json');
        echo Zend_Json::encode($json);
    }

    public function saveIntoSessionAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->getHelper('layout')->disableLayout();
        $request = $this->getRequest();
        $params = $request->getParams();

        Model_Session::saveToSession('competiton_id', $params['id']);
        
        header('Content-type: application/json');
        echo Zend_Json::encode(array('status' => 200));
    }

    public function tournirTableDetalAction() {
        $id = $this->_request->getParam("competition_id");
//        Zend_Debug::fdump($id,'$id');
        if (!empty($id)) {
            $comp = $this->competitionsModel->find($id)->current();
            $results = $comp->getResultsTable();
        }
        $this->view->resultsTable = $results;
    }

    public function tournirTableMiniAction() {
        $session = Model_Session::getSession();
        if (!empty($session->competiton_id)) {
            $comp = $this->competitionsModel->find($session->competiton_id)->current();
            $results = $comp->getResultsTable();
        }
        $this->view->resultsTable = $results;
        $this->view->competition = $comp;
    }

    public function listAction() {
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/fancybox/jquery.mousewheel-3.0.4.pack.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/fancybox/jquery.fancybox-1.3.4.pack.js');
        $this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/fancybox/jquery.fancybox-1.3.4.css');
    }

}

?>
