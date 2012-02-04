<?php

/**
 * Description of PlayersController
 *
 * @author KHOROZ
 */
class ClubController extends App_AbstractController {

    protected $clubsModel;
    protected $competitionsModel;

    public function preDispatch() {
        $this->clubsModel = new Model_Clubs();
        $this->competitionsModel = new Model_Competitions();
    }

    /**
     * The controller's init() function is called before
     * the action. Usually we use it to set up the ACL
     * restrictions for the actions within the controller.
     *
     */
    public function init() {
        $this->_modelName = 'club';
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $this->_helper->ajaxContext->setAutoJsonSerialization(false)
                ->addActionContext('view', 'html')
                ->addActionContext('index', 'html')
                ->addActionContext('manage', array('html'))
                ->addActionContext('createUpdate', array('json'))
                ->addActionContext('delete', 'html')
                ->initContext();
    }

    /**
     * This action is the home page of the website
     *
     */
    public function indexAction() {
        $this->view->title = 'Список команд';
        $this->view->headTitle('Список команд');
        $competitions = $this->competitionsModel->fetchCompetitions(array());
        $this->view->competitions = $competitions;
        parent::indexAction();
    }

    public function manageAction() {
        $this->view->title = 'Клуби (Admin part)';
        $this->view->headTitle('Клуби');
        parent::manageAction();
    }

    public function viewAction() {
        parent::viewAction();
        $this->view->headTitle('Clubs');

        $id = (int) $this->getRequest()->getParam('id');
        if ($id == 0) {
            throw new Exception('Unknown club');
            return;
        }

        $club = $this->clubsModel->find($id)->current();
        if ($club->id != $id) {
            $this->_redirect('/' . $this->getRequest()->getControllerName() . '/');
            return;
        }
        $this->view->club = $club;
        $this->view->title = $club->fullName();
    }

    public function createUpdateAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $clubForm = new Form_ClubForm();
        $clubForm->setMethod('POST');
        $data = array();

        if ($this->getRequest()->isPost()) {
            if ($clubForm->isValid($_POST)) {
                $result = $this->clubsModel->createUpdate(
                                $clubForm->getValue('club_id'), $clubForm->getValue('name'), $clubForm->getValue('location'), $clubForm->getValue('treiner'), $clubForm->getValue('description'), $clubForm->getValue('gallery_id')
                );
                $data['id'] = $result;
            } else {
                $data['errors'] = $clubForm->getMessages();
            }
        } else {
            $id = $this->_request->getParam("id");
            if (!empty($id)) {
                $club = $this->clubsModel->find($id)->current();
                $clubForm->prefill($club);
                $this->view->club = $club;
            }
            $gallery = null;
            $sessionCustom = new Zend_Session_Namespace('custom');
            if (!empty($club)) {
                if (!empty($club->gallery_id)) {
                    $gallery = $club->getGallery();
                } else {
                    $defGallery = new Model_Galleries();
                    $gallery = $defGallery->createGallery('club');
                    $club->gallery_id = $gallery->id;
                    $club->save();
                    $clubForm->getElement('gallery_id')->setValue($gallery->id);
                }
            } else {

                $defGallery = new Model_Galleries();
                $gallery = $defGallery->createGallery('club');
                $clubForm->getElement('gallery_id')->setValue($gallery->id);
            }
            $sessionCustom->galleryObject = $gallery;
        }
        $this->view->form = $clubForm;
        $content = $this->view->render('club/create-update.json.phtml');
        $data['content'] = $content;
        $this->_helper->json($data);
    }

    public function responsibilityAction() {
        $clubs = $this->clubsModel->fetchClubs();

        $this->view->clubsCompetitions = array();

        foreach ($clubs as $c) {
            $tempArray = array();
            $tempArray['name'] = $c->name;
            $tempArray['competitions'] = array();

            $cache = Zend_Registry::get('cache');

            if (($competitions = $cache->load('club_responsibility' . $c->id)) === false) {
                $competitions = $c->findManyToManyRowset('Model_Competitions', 'Model_CompetitionsClubs');
                $cache->save($competitions, 'club_responsibility' . $c->id);
            }

            foreach ($competitions as $competitionRow) {
                $tempArray['competitions'][] = $competitionRow->name;
            }

            $this->view->clubsCompetitions[] = $tempArray;
        }
    }

}

?>
