<?php

/**
 * Description of Files
 *
 * @author KHOROZ
 */
class Model_Files extends Zend_Db_Table_Abstract {

    protected $_name = 'files';
    protected $_rowClass = 'Model_File';
    protected $_referenceMap = array(
        'Gallery' => array(
            'columns' => array('gallery_id'),
            'refTableClass' => 'Model_Galleries',
            'refColumns' => 'id')
    );

    public function saveFile($galleryId, $name, $path, $url, $size, $mimetype) {
        $file = $this->createRow(); //new Model_File();
        $file->gallery_id = $galleryId;
        $file->name = $name;
        $file->path = $path;
        $file->url = $url;
        $file->size = $size;
        $file->mimetype = $mimetype;

        $file->save();

        return intval($file->id);
    }

    public function findByUrl($url) {
        $select = $this->select()->
                where('url=?', $url);
        return parent::fetchRow($select);
    }

}

?>
