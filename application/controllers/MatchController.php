<?php

/**
 * Description of MatchController
 *
 * @author KHOROZ
 */
class MatchController extends App_AbstractController {

    protected $competitionsModel;
    protected $clubsModel;
    protected $toursModel;
    protected $matchsModel;

    public function init() {
        $this->_modelName = 'match';
        // AJAX
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $this->_helper->ajaxContext->setAutoJsonSerialization(false)
                ->addActionContext('createUpdate', array('json', 'html'))
                ->initContext();
    }

    public function preDispatch() {
        $this->competitionsModel = new Model_Competitions();
        $this->clubsModel = new Model_Clubs();
        $this->matchsModel = new Model_Matches();
        $this->toursModel = new Model_Tours();
    }

    //put your code here
    public function createUpdateAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $compId = (int) $this->getRequest()->getParam('comp_id');
        $tourId = (int) $this->getRequest()->getParam('tour_id');

        $competition = $this->competitionsModel->find($compId)->current();
        $tour = $this->toursModel->find($tourId)->current();

        $matchForm = new Form_MatchForm($competition, $tour);
        $matchForm->setMethod('POST');
        $data = array();
        $match = null;
        if ($this->getRequest()->isPost()) {
            if ($matchForm->isValid($_POST)) {
                $score = '';
                if (is_numeric($matchForm->getValue('owner_score')) && is_numeric($matchForm->getValue('guest_score'))) {
                    $score = $matchForm->getValue('owner_score') . ':' . $matchForm->getValue('guest_score');
                }
                $match = $this->matchsModel->createUpdate(
                                $matchForm->getValue('match_id'), $matchForm->getValue('match_date'), $matchForm->getValue('owner_club_id'), $matchForm->getValue('guest_club_id'), $score, $matchForm->getValue('tour_id'), $matchForm->getValue('match_status_id'), $matchForm->getValue('description'), $matchForm->getValue('gallery_id')
                );
                $data['id'] = $match->id;
                $match->getGallery()->confirm();
            } else {
                $data['errors'] = $matchForm->getMessages();
            }
        } else {
            $id = $this->_request->getParam("id");
//            Zend_Debug::fdump($id,'match $id');
            if (!empty($id)) {
                $match = $this->matchsModel->find($id)->current();
                $matchForm->prefill($match);
                $this->view->match = $match;
            }
            $gallery = null;
            $sessionCustom = new Zend_Session_Namespace('custom');
            if (!empty($match)) {
                if (!empty($match->gallery_id)) {
                    $gallery = $match->getGallery();
                } else {
                    $defGallery = new Model_Galleries();
                    $gallery = $defGallery->createGallery('match', $compId);
                    $match->gallery_id = $gallery->id;
                    $match->save();
                    $matchForm->getElement('gallery_id')->setValue($gallery->id);
                }
            } else {

                $defGallery = new Model_Galleries();
                $gallery = $defGallery->createGallery('match', $compId);
                $matchForm->getElement('gallery_id')->setValue($gallery->id);
            }
            $sessionCustom->galleryObject = $gallery;
        }

        $this->view->form = $matchForm;
        $content = $this->view->render('match/create-update.json.phtml');
        $data['content'] = $content;
        $this->_helper->json($data);
    }

    public function viewAction() {
        parent::viewAction();
        $this->view->headTitle('Match');
        $id = (int) $this->getRequest()->getParam('id');
        if ($id == 0) {
            throw new Exception('Unknown match');
            return;
        }

        $match = $this->matchsModel->find($id)->current();
        ;
        $this->view->match = $match;
        $this->view->title = $match->getMatchTitle();
    }

}

?>
