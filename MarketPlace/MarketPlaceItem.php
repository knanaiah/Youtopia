<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MarketPlace
 *
 * @author Kajal.Nanaiah
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/marketplace/MarketPlaceDAOImpl.php';

class MarketPlaceItem {
    //put your code here
    
    var $loginId;
    var $communityId;
    var $catId;
    var $subcatId;
    var $price;
    var $condition;
    var $description;
    var $file;
    var $filename;
    var $status;
    var $createDate;
    
    public function buildMarketPlaceItem($catId, $subcatId, $price, $condition, 
            $description, $file, $filename, $loginId, $communityId) {
        $this->loginId = $loginId;
        $this->communityId = $communityId;
        $this->catId = $catId;
        $this->subcatId = $subcatId;
        $this->price = $price;
        $this->condition = $condition;
        $this->description = $description;
        $this->file = $file;
        $this->filename = $filename;
        $this->status = 'ACTIVE';
        //use a function to get creation date
        $this->createDate = date('Y-m-d');
    }
    
    //Create and Update Market Place Item
    public function createMarketPlaceItem($json_marketplace) {
        //print $json_marketplace;
        //var_dump($json_marketplace);
        $marketplaceDAO = new MarketPlaceDAOImpl();
        $marketplaceDAO->save($json_marketplace);
    }
    
    public static function delete($json_marketplace) {
        $marketplaceDAO = new MarketPlaceDAOImpl();
        $marketplaceDAO->delete($json_marketplace);
    }
    
    /*public function printService() {
        print $this->loginId;
        print $this->communityId;
        print $this->postTitle;
        print $this->postDescription;
        print $this->status;        
        print $this->CreateDate;
    }*/
}
