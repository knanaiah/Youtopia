<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Posting
 *
 * @author dhigley
 */
class Posting {
    //put your code here
    
    private $Id;
    private $loginId;
    private $communityId;    
    private $title;
    private $description;
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
    public function getCommunityId() {
        return $this->communityId;
    }
    public function setCommunityId($communityId) {
        $this->communityId = $communityId;
    }
    public function getTitle() {
        return $this->title;
    }
    public function setTitle($title) {
        $this->title = $title;
    }
    public function getDescription() {
        return $this->description;
    }
    public function setDescription($description) {
        $this->description = $description;
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
