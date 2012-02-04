<?php

/**
 * Login_Acl
 *
 * @author Enrico Zimuel (enrico@zimuel.it)
 */
class App_Login_Acl extends Zend_Acl {

    /**
     * __construct
     *
     * @param Zend_Db_Adapter $db
     * @param integer $role
     */
    public function __construct($db, $role) {
//        Zend_Debug::dump($role,'$role from App_Login_Acl');
        $this->loadRoles($db);
        $roles = new Model_Roles($db);
        $inhRole = $role;
        while (!empty($inhRole)) {
            $this->loadResources($db, $inhRole);
            $this->loadPermissions($db, $inhRole);
            $inhRole = $roles->getParentRole($inhRole);
            Zend_Debug::fdump($inhRole,'$inhRole');
        }
    }

    /**
     * Load all the roles from the DB
     *
     * @param Zend_Db_Adapter $db
     * @return boolean
     */
    public function loadRoles($db) {
        if (empty($db)) {
            return false;
        }
        $roles = new Model_Roles($db);
        $allRoles = $roles->getRoles();
        foreach ($allRoles as $role) {
            if (!empty($role->id_parent)) {
                $this->addRole(new Zend_Acl_Role($role->id), $role->id_parent);
            } else {
                $this->addRole(new Zend_Acl_Role($role->id));
            }
        }
        return true;
    }

    /**
     * Load all the resources for the specified role
     *
     * @param Zend_Db_Adapter $db
     * @param integer $role
     * @return boolean
     */
    public function loadResources($db, $role) {
        if (empty($db)) {
            return false;
        }
        $resources = new Model_Resources($db);
        $allResources = $resources->getResourcesByRole($role);


        foreach ($allResources as $res) {
            if (!$this->has($res)) {
                $this->addResource(new Zend_Acl_Resource($res['resource']));
            }
        }
        
        return true;
    }

    /**
     * Load all the permission for the specified role
     *
     * @param Zend_Db_Adapter $db
     * @param integer $role
     * @return boolean
     */
    public function loadPermissions($db, $role) {
        if (empty($db)) {
            return false;
        }
        $permissions = new Model_Permissions($db);
        $allPermissions = $permissions->getPermissionsByRole($role);
        foreach ($allPermissions as $res) {
            if ($res['permission'] == 'allow') {
                $this->allow($res['id_role'], $res['resource']);
            } else {
                $this->deny($res['id_role'], $res['resource']);
            }
        }
        return true;
    }

}
