<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Service
 *
 * @author Kajal.Nanaiah
 */

include("ServiceName.php");
include("ServiceType.php");
//require_once 'DBConnect.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/services/ServiceListingDAOImpl.php';

//This class should be renamed as ServiceClient
class Service {
    var $id;
    var $loginId;
    var $svcId;
    var $communityId;
    var $serviceDescription;
    var $status;
    var $CreateDate;
    
    public function buildService($serviceCode, $serviceDescription, $loginId, $communityId) {
        $this->loginId = $loginId;
        $this->communityId = $communityId;
        $this->svcId = $this->getServiceID($serviceCode);
        $this->serviceDescription = $serviceDescription;
        $this->status = 'ACTIVE';
        //use a function to get creation date
        $this->createDate = date('Y-m-d');
    }
    
    public function getServiceID($serviceCode) {
        $result = mysql_query("select id from services where code = '" . $serviceCode . "'");   
        if (mysql_num_rows($result) != 0) { 
            $row = mysql_fetch_array($result);
            $svcId = $row['id'];
        }
        return $svcId;
    }

    //Create and Update Service 
    public function createService($json_service) {
        $serviceDAO = new ServiceListingDAOImpl();
        $serviceDAO->save($json_service);
    }
    
    public static function delete($json_service) {
        $serviceDAO = new ServiceListingDAOImpl();
        $serviceDAO->delete($json_service);
    }
    
    public function printService() {
        print $this->loginId;
        print $this->communityId;
        print $this->svcId;
        print $this->ServiceDescription;
        print $this->status;        
        print $this->CreateDate;
    }
}
