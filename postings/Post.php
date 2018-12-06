<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Posting
 *
 * @author Kajal.Nanaiah
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/postings/PostingDAOImpl.php';

class Post {
    //put your code here
    
    var $loginId;
    var $communityId;
    var $postTitle;
    var $postDescription;
    var $status;
    var $createDate;
    
    public function buildPost($postTitle, $postDescription, $loginId, $communityId) {
        $this->loginId = $loginId;
        $this->communityId = $communityId;
        $this->postTitle = $postTitle;
        $this->postDescription = $postDescription;
        $this->status = 'ACTIVE';
        //use a function to get creation date
        $this->createDate = date('Y-m-d');
    }
    
    //Create and Update post
    public function createPost($json_post) {
        $postDAO = new PostingDAOImpl();
        $postDAO->save($json_post);
    }
    
    public static function delete($json_post) {
        $postDAO = new PostingDAOImpl();
        $postDAO->delete($json_post);
    }

    public function printService() {
        print $this->loginId;
        print $this->communityId;
        print $this->postTitle;
        print $this->postDescription;
        print $this->status;        
        print $this->CreateDate;
    }

}
