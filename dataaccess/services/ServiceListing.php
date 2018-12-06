<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceListing
 *
 * @author Kajal.Nanaiah
 */
class ServiceListing {
    //put your code here
    
    private $Id;
    private $description;
    private $svcId;
    private $loginId;
    private $communityId;
    private $status;
    private $createDt;

    public function getId() {
        return $this->Id;
    }
    public function setId($Id) {
        $this->Id = $Id;
    }
    public function getDescription() {
        return $this->description;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function getSvcId() {
        return $this->svcId;
    }
    public function setSvcId($svcId) {
        $this->svcId = $svcId;
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
