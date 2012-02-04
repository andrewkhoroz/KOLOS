<?php

/**
 * Description of Model_Club
 *
 * @author KHOROZ
 */
class Model_Club extends Zend_Db_Table_Row_Abstract implements App_IGalerryable {

    protected $_primary = 'id';

    /**
     * Gets path to club's logo
     * @return string
     */
    public function getLogoPath() {
//        return '/img/clubs/clubs_' . $this->id . '.png';
        $galleryFiles = $this->getGallery()->getGalleryFiles();
        if (count($galleryFiles) > 0){
            return $galleryFiles[0]->url;
        }else{
            return '';
        }
    }

    /**
     *
     * @return Model_Gallery 
     */
    public function getGallery() {
        $galleriesModel = new Model_Galleries();
        $gallery = $galleriesModel->find($this->gallery_id)->current();
        return $gallery;
    }

    public function fullName() {
        return $this->name . '<span style="color:#757575">(' . $this->location . ')</span>';
    }

}

?>
