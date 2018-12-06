<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceListingDAOFactory
 *
 * @author Kajal.Nanaiah
 */
class ServiceListingDAOFactory {
    //put your code here
    
    public function getServiceListingDAO($type) {
        
        // use case statement here
        if ($type == 'mysql') {
            return new ServiceListingDAOImpl();
        } else {
            return new ServiceListingDAOImpl();
        }
    }
    
    public function getClubListingDAO($type) {
        //code here
    }
}
