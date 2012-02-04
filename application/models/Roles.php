<?php

/**
 * Model_Roles
 *  
 * @author Enrico Zimuel (enrico@zimuel.it)
 */
class Model_Roles extends Zend_Db_Table_Abstract {
    
    const GUEST=1;
    const ADMIN=10;

    
    protected $_name = 'roles';
    protected $_primary = 'id';
    protected $_rowClass = 'Model_Role';
    protected $_dependentTables = array('Users', 'Permissions');

    /**
     * getRoles
     * 
     * @return object
     */
    public function getRoles() {
        return $this->fetchAll(null, 'id');
    }

    /**
     * getParentRole
     *
     * @param integer $role
     * @return integer|boolean
     */
    public function getParentRole($role) {
        $select = $this->select('parent_id')
                ->from(array('r' => 'roles'))
                ->where('r.id=?', $role);
        $record = $this->fetchRow($select);
        if (!empty($record['parent_id'])) {
            return $record['parent_id'];
        }
        return false;
    }

}