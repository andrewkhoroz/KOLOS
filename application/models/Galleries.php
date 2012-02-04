<?php

/**
 * Description of Galleries
 *
 * @author KHOROZ
 */
class Model_Galleries extends Zend_Db_Table_Abstract {

    protected $_name = 'galleries';
    protected $_rowClass = 'Model_Gallery';
    protected $_dependentTables = array('Model_Matches', 'Model_Files','Model_Competitions');
    protected $_referenceMap = array(
        'Model_Competitions' => array(
            'columns' => array('competition_id'),
            'refTableClass' => 'Model_Competitions',
            'refColumns' => 'id')
    );

    public function createGallery($name, $compId=112, $isConfirmed=0) {

        $defGallery = $this->createRow();
        $defGallery->name = $name;
        $defGallery->competition_id = $compId;
        $defGallery->is_confirmed = $isConfirmed;
        $defGallery->save();
        $path = '/uploads/'.date('Y') . '/' . date('m') . '/' . $defGallery->id . '/';
        $defGallery->path = $path;
        $defGallery->save();
        return $defGallery;
    }

}

?>
