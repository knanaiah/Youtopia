<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MarketPlace
 *
 * @author dhigley
 */
class MarketPlace {
    //put your code here
    
    private $Id;
    private $loginId;
    private $communityId;
    private $catId;
    private $subCatId;
    private $price;
    private $condition;
    private $description;
    private $file;
    private $filename;
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
    public function getCatId() {
        return $this->catId;
    }
    public function setCatId($catId) {
        $this->catId = $catId;
    }
    public function getSubCatId() {
        return $this->subCatId;
    }
    public function setSubCatId($subCatId) {
        $this->subCatId = $subCatId;
    }
    public function getPrice() {
        return $this->price;
    }
    public function setPrice($price) {
        $this->price = $price;
    }
    public function getCondition() {
        return $this->condition;
    }
    public function setCondition($condition) {
        $this->condition = $condition;
    }
    public function getDescription() {
        return $this->description;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function getFile() {
        return $this->file;
    }
    public function setFile($file) {
        $this->file = $file;
    }
        public function getFileName() {
        return $this->filename;
    }
    public function setFileName($filename) {
        $this->filename = $filename;
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
