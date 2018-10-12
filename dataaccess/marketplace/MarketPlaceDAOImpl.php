<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MarketPlaceDAOImpl
 *
 * @author dhigley
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/DataAccessObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/DBConnect.php';
include('MarketPlace.php');

class MarketPlaceDAOImpl {
    //put your code here

    var $marketplace;
    var $recordCount = 0;
    
    //Connect to database
    private function getDBConnection() {
        $dbObj = new DBConnect();
        $dbObj->selectDB();
    }

    public function MarketPlaceDAOImpl() {
        $this->getDBConnection();
        //$this->findAll();
        //$this->findByPostId($postId);
    }
   
    public function createMarketPlaceItem($marketPlaceObj) {
        $marketplace = (array)$marketPlaceObj;
        $marketPlaceItem = new MarketPlace();
        
        if ($marketplace['id'] != '') {
            $marketPlaceItem->setId($marketplace['id']);
            error_log("ITEM ID: " . $marketPlaceItem->getId());
        }

        $marketPlaceItem->setLoginId($marketplace['loginId']);
        $marketPlaceItem->setCommunityId($marketplace['communityId']);
        $marketPlaceItem->setCatId($marketplace['catId']);
        $marketPlaceItem->setSubCatId($marketplace['subcatId']);
        $marketPlaceItem->setPrice($marketplace['price']);
        $marketPlaceItem->setCondition($marketplace['condition']);
        $marketPlaceItem->setDescription($marketplace['description']);
        $marketPlaceItem->setFile($marketplace['file']);
        $marketPlaceItem->setFileName($marketplace['filename']);
        $marketPlaceItem->setStatus($marketplace['status']);
        $marketPlaceItem->setCreateDate($marketplace['createDate']);
        
        return $marketPlaceItem;
    }
    
    protected function execute($query) {
        $res = mysql_query($query);
        
        $recordCount = mysql_num_rows($res);
        
        if($recordCount > 0) {
            for($i = 0; $i < mysql_num_rows($res); $i++) {
                $row = mysql_fetch_assoc($res);
                print json_encode((array)$row);
                $marketplace[$i] = new MarketPlace();
                $marketplace[$i]->setId($row['Id']);
                $marketplace[$i]->setLoginId($row['LoginId']);
                $marketplace[$i]->setCommunityId($row['CommunityId']);
                $marketplace[$i]->setCatId($row['CatId']);
                $marketplace[$i]->setSubCatId($row['SubCatId']);
                $marketplace[$i]->setPrice($row['Price']);
                $marketplace[$i]->setCondition($row['ItemCondition']);
                $marketplace[$i]->setDescription($row['Description']);
                $marketplace[$i]->setFile($row['Image']);
                $marketplace[$i]->setFileName($row['ImageName']);                
                $marketplace[$i]->setStatus($row['Status']);
                $marketplace[$i]->setCreateDate($row['CreateDate']);
                //print json_encode((array)($marketplace[$i]));
            }
        }
        /*var_dump($marketplace);
        foreach ($marketplace as $item) {
                        print json_encode((array)$item);
        }*/
    }
        
    //Create Market Place Item - Persist to DB
    public function save($marketPlaceItem) {
        error_log("SAVING");
        $affectedRows = 0;
        $marketPlaceDB = array();
        $mpItem = $this->createMarketPlaceItem(json_decode($marketPlaceItem), true);
         
        try {
            if($mpItem->getId() != '') {
                $marketPlaceDB[$this->recordCount] = $this->findById($mpItem->getId());
            }

            // If the DB query returned a row then update,
            // otherwise insert a new user.
            if(sizeof($marketPlaceDB) > 0) {
                $query = "UPDATE marketplaceitems SET ".
                    "price='".$mpItem->getPrice()."', ".
                    //"itemcondition='".$mpItem->getCondition()."', ".                        
                    "description='".$mpItem->getDescription()."', ".
                    //"image='".$mpItem->getFile()."', ".                        
                    //"imagename='".$mpItem->getFileName()."', ".                                                
                    "status='".$mpItem->getStatus()."' ".
                    "WHERE id=".$mpItem->getId();

                mysql_query($query);
                $affectedRows = mysql_affected_rows();
            } else {
                $query = "INSERT INTO marketplaceitems (LoginId, CommunityId, CatId, SubCatId, "
                        . "Price, ItemCondition, Description, Image, ImageName, Status, CreateDate) VALUES('".
                $mpItem->getLoginId()."', '".
                $mpItem->getCommunityId()."', '".
                $mpItem->getCatId()."', '".
                $mpItem->getSubCatId()."', '".                        
                $mpItem->getPrice()."', '".
                $mpItem->getCondition()."', '".  
                $mpItem->getDescription()."', '".  
                $mpItem->getFile()."', '".                        
                $mpItem->getFileName()."', '".                                                
                $mpItem->getStatus()."', '". 
                $mpItem->getCreateDate()."')";       

                if (!mysql_query($query)) {
                    die(mysql_error());
                }
                $affectedRows = mysql_affected_rows();
            }
        } catch (Exception $e) {
            print $e->getMessage();
            print $e->getTrace();
        }
        return $affectedRows;
    }

    //Delete a users selected market place item(s)
    public function delete($Id) {
        try {        
            $query = "delete from marketplaceitems where Id in (" . implode(',',array_values(json_decode($Id))) . ")";
            mysql_query($query);
        } catch (Exception $e) {
            $e->getMessage();
            $e->getTrace();
        }
    }
    
    //Retrieve a marketplace item by its unique ID
    public function findById($itemId) {
        $query = "select * from marketplaceitems where id = " .$itemId;
        return $this->execute($query);
    }
    
}
