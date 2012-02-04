<?php

class Model_Users extends Zend_Db_Table_Abstract {

    protected $_name = 'users';
    protected $_rowClass = 'Model_User';
    protected $_referenceMap = array(
        'Role' => array(
            'columns' => 'role_id',
            'refTableClass' => 'Roles',
            'refColumns' => 'id'
            ));

    /**
     * Check if a username is a Ldap user
     * 
     * @param string $username
     * @return boolean
     */
    public function isLdapUser($username) {
        if (empty($username)) {
            return false;
        }
        $select = $this->select()->where('username = ?', $username)
                ->where('ldap = true');
        $result = $this->fetchRow($select);
        return!empty($result);
    }

    /**
     * Get the role Id of a user
     * 
     * @param string $username
     * @return integer|boolean
     */
    public function getRoleId($username) {
        if (empty($username)) {
            return false;
        }
        $result = $this->find($username);
        if (!empty($result)) {
            return $result[0]['role_id'];
        }
        return false;
    }

    public function createUser($username, $password, $firstName, $lastName, $role) {
        // create a new row
        $rowUser = $this->createRow();
        if ($rowUser) {
            // update the row values
            $rowUser->username = $username;
            $rowUser->password = md5($password);
            $rowUser->first_name = $firstName;
            $rowUser->last_name = $lastName;
            $rowUser->role_id = $role;
            $rowUser->save();
            //return the new user
            return $rowUser;
        } else {
            throw new Zend_Exception("Could not create user! ");
        }
    }

    public static function getUsers() {
        $userModel = new self();
        $select = $userModel->select();
        $select->order(array('last_name', 'first_name'));
        return $userModel->fetchAll($select);
    }

    public function updateUser($id, $username, $firstName, $lastName, $role) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();

        if ($rowUser) {
            // update the row values
            $rowUser->username = $username;
            $rowUser->first_name = $firstName;
            $rowUser->last_name = $lastName;
            $rowUser->role_id = $role;
            $rowUser->save();
            //return the updated user
            return $rowUser;
        } else {
            throw new Zend_Exception("User update failed.  User not found!");
        }
    }

    public function updatePassword($id, $password) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();

        if ($rowUser) {
            //update the password
            $rowUser->password = md5($password);
            $rowUser->save();
        } else {
            throw new Zend_Exception("Password update failed.  User not found!");
        }
    }

    public function deleteUser($id) {
        // fetch the user's row
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            $rowUser->delete();
        } else {
            throw new Zend_Exception("Could not delete user.  User not found!");
        }
    }

}