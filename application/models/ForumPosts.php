<?php

/**
 * Description of ForumPost
 *
 * @author KHOROZ
 */
class Model_ForumPosts extends Zend_Db_Table_Abstract implements Zend_Acl_Resource_Interface {

    protected $_name = 'forum_posts';

    public function getResourceId() {
        return 'forumPost';
    }

}
?>
