<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Comment
 *
 * @author Kajal.Nanaiah
 */
class Comment {
    //put your code here
    
    private $Id;
    private $loginId;
    private $postId;
    private $comment;
    private $status;
    private $createDt;

    public function getId() {
        return $this->Id;
    }
    public function setId($Id) {
        $this->Id = $Id;
    }
    public function getLoginId() {
        return $this->loginId;
    }
    public function setLoginId($loginId) {
        $this->loginId = $loginId;
    }
    public function getPostId() {
        return $this->postId;
    }
    public function setPostId($postId) {
        $this->postId = $postId;
    }
    public function getComment() {
        return $this->comment;
    }
    public function setComment($comment) {
        $this->comment = $comment;
    }
    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }
    public function getCreateDate() {
        return $this->createDt;
    }
    public function setCreateDate($createDate) {
        $this->createDt = $createDate;
    }
}
