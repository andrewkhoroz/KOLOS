<?php

/**
 * Login_Model_Permissions
 *  
 * @author Enrico Zimuel (enrico@zimuel.it)
 */
class Model_Permissions extends Zend_Db_Table_Abstract
{

    protected $_name = 'permissions';
    protected $_primary = 'id';
    protected $_referenceMap = array(
        'Role' => array(
            'columns' => 'role_id',
            'refTableClass' => 'Roles',
            'refColumns' => 'id'
        ),
        'Resource' => array(
            'columns' => 'resource_id',
            'refTableClass' => 'Resources',
            'refColumns' => 'id'
            ));

    /**
     * getPermissions
     * 
     * @param integer $role
     * @return array 
     */
    public function getPermissionsByRole($role)
    {
        $select = $this->getAdapter()->select();
        $select->from(array('p' => 'permissions'))
                ->join(array('r' => 'resources'), 'r.id=p.resource_id');
        if (!empty($role)) {
            $select->where('p.role_id=?', $role);
        }
        $stmt = $this->getAdapter()->query($select);
        return $stmt->fetchAll();
    }

}