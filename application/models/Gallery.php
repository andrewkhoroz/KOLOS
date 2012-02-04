<?php

/**
 * Description of Model_Gallery
 *
 * @author KHOROZ
 */
class Model_Gallery extends Zend_Db_Table_Row_Abstract {

    protected $_primary = 'id';

    public function getGalleryFiles() {
        $files = $this->findDependentRowset('Model_Files', 'Gallery');
        return $files;
    }

    public function confirm() {
        $this->is_confirmed = 1;
        $this->save();
        
    }

}

?>
