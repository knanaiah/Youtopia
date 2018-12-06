<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Comments
 *
 * @author Kajal.Nanaiah
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/comments/CommentDAOImpl.php';

class Comments {
    //put your code here
    
    var $loginId;
    var $postId;
    var $postComment;
    var $status;
    var $createDate;
    
    public function buildPost($loginId, $postId, $postComment) {
        $this->loginId = $loginId;
        $this->postId = $postId;
        $this->postComment = $postComment;
        $this->status = 'ACTIVE';
        //use a function to get creation date
        $this->createDate = date('Y-m-d');
    }
    
    //Create and Update comment
    public function createComment($json_comment) {
        $commentDAO = new CommentDAOImpl();
        $commentDAO->save($json_comment);
    }
    
    public static function delete($json_comment) {
        $commentDAO = new CommentDAOImpl();
        $commentDAO->delete($json_comment);
    }

    public function printService() {
        print $this->loginId;
        print $this->postId;
        print $this->postComment;
        print $this->status;        
        print $this->CreateDate;
    }        
}
