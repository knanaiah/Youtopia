<?php
    
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceListingDAOImpl
 *
 * @author dhigley
 */

include('ServiceListing.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/DataAccessObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/DBConnect.php';
        
class ServiceListingDAOImpl implements DataAccessObject {
    //put your code here
    var $serviceListing;
    var $recordCount = 0;
    
    //Connect to database
    private function getDBConnection() {
        $dbObj = new DBConnect();
        $dbObj->selectDB();
    }
 
    public function ServiceListingDAOImpl() {
        $this->getDBConnection();
        //$this->Test_createService();
        $this->findAll();
    }
   
    public function createService($serviceObj) {
        //$date = date('Y-m-d');
        $service = (array)$serviceObj;
        $serviceListing = new ServiceListing();
        
        if ($service['id'] != '') {
            $serviceListing->setId($service['id']);
        }
        $serviceListing->setLoginId($service['loginId']);
        $serviceListing->setCommunityId($service['communityId']);
        $serviceListing->setSvcId($service['svcId']);
        $serviceListing->setDescription($service['serviceDescription']);
        $serviceListing->setStatus($service['status']);
        $serviceListing->setCreateDate($service['createDate']);
        
        //$this->save($serviceListing);
        return $serviceListing;
    }
    
    protected function execute($query) {
        $res = mysql_query($query);
        
        $recordCount = mysql_num_rows($res);
        
        if($recordCount > 0) {
            for($i = 0; $i < mysql_num_rows($res); $i++) {
                $row = mysql_fetch_assoc($res);
                print json_encode((array)$row);
                $serviceListing[$i] = new ServiceListing();
                $serviceListing[$i]->setId($row['Id']);
                $serviceListing[$i]->setCommunityId($row['CommunityId']);
                $serviceListing[$i]->setLoginId($row['LoginId']);
                $serviceListing[$i]->setSvcId($row['SvcId']);
                $serviceListing[$i]->setDescription($row['Description']);
                $serviceListing[$i]->setStatus($row['Status']);
                $serviceListing[$i]->setCreateDate($row['CreateDate']);
                print json_encode((array)($serviceListing[$i]));
            }
        }
    }
        
    //Create and Update a Service
    public function save($serviceListing) {
        $affectedRows = 0;
        $serviceListingDB = array();
        $service = $this->createService(json_decode($serviceListing), true);
        
        try {
        if($service->getId() != '') {
            $serviceListingDB[$this->recordCount] = $this->findById($service->getId());
        }
        
        // If the query returned a row then update,
        // otherwise insert a new user.
        if(sizeof($serviceListingDB) > 0) {
            $query = "UPDATE servicelisting SET ".
                "description='".$service->getDescription()."', ".
                "status='".$service->getStatus()."' ".
                "WHERE id=".$service->getId();
            
            mysql_query($query);
            $affectedRows = mysql_affected_rows();
        }
        else {
            $query = "INSERT INTO servicelisting (LoginId, SvcId, CommunityId, Description, Status, CreateDate) VALUES('".
                $service->getLoginId()."', '".
                $service->getSvcId()."', '".
                $service->getCommunityId()."', '".
                $service->getDescription()."', '".
                $service->getStatus()."', '". 
                $service->getCreateDate()."')";                    
            
            mysql_query($query);
            $affectedRows = mysql_affected_rows();
        }} catch (Exception $e) {
            $e->getMessage();
            $e->getTrace();
        }
        return $affectedRows;
    }

    //Delete a users selected listing(s)
    public function delete($Id) {
        try {
            
            $query = "delete from servicelisting where Id in (" . implode(',',array_values(json_decode($Id))) . ")";
            mysql_query($query);
        } catch (Exception $e) {
            $e->getMessage();
            $e->getTrace();
        }
    }
    
    //Retrieve all listings
    public function findAll() {
        $query = "select * from servicelisting";
        return $this->execute($query);
    }
    
    //Retrieve a listing by its unique ID
    public function findById($Id) {
        $query = "select * from servicelisting where id = " .$Id;
        return $this->execute($query);
    }

    //Retrieve all listings for a community
    public function getServiceListingByCommunity($communityId) {
        $query = "select * from servicelisting where communityId = " .$communityId;
        return $this->execute($query);
    }

    //Retrieve all lisitngs for a user
    public function getServiceListingByLogin($loginId) {
        $query = "select * from servicelisting where loginId = " .$loginId;
        return $this->execute($query);
    }
    
    //Retrieve all listings within selected radius
    public function getServiceListingByRadius($radius, $communityId) {
        $query = "select * from servicelisting where communityId = " .$communityId . " and radius = " . $radius;
        return $this->execute($query);
    }
}
