<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract
 *
 * @author ANDREW
 */
class Model_Abstract extends Zend_Db_Table_Abstract {

    //get paginator adapter (for paging)
    public function fetchPaginatorAdapter($searchParams, Zend_Db_Select $customSelect=null) {
        if (empty($customSelect)) {
            $select = $this->select();
            $select->order('id DESC');
        } else {
            $select = $customSelect;
        }
        foreach ($searchParams as $key => $param) {
            if (!empty($param)) {
                $select->where($key . " LIKE ?", '%' . $param . '%');
            }
        }
//        Zend_Debug::fdump($select->__toString(),'$select');
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        return $adapter;
    }

    public function remove($id) {
        $object = $this->find($id)->current();
//        Zend_Debug::fdump($object, '$object');
        if ($object) {
            $object->delete();
            return true;
        } else {
            throw new Zend_Exception("Delete function failed; coud not find row !");
        }
    }

}

?>
