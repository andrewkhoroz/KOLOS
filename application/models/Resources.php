<?php
/**
 * Model_Resources
 *  
 * @author Enrico Zimuel (enrico@zimuel.it)
 */
class Model_Resources extends Zend_Db_Table_Abstract
{
    protected $_name = 'resources';
    protected $_primary = 'id';
    protected $_dependentTables = array('Permissions');

    /**
     * Повертає ресурси для ролі
     *
     * @param integer $role
     * @return array
     */
    public function getResourcesByRole($role) {
    	$select= $this->getAdapter()->select();
        $select->from(array('r'=>'resources'))
               ->join(array('p'=>'permissions'),'r.id=p.resource_id');
        if (!empty($role)) {       
        	$select->where('p.role_id=?',$role);
        }       
        $stmt= $this->getAdapter()->query($select);
    	return $stmt->fetchAll();
    }
    
}