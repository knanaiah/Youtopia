<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAOFactory
 *
 * @author dhigley
 */

include ('ServiceListingDAOFactory.php');

class DAOFactory {
    //put your code here
    const DAOSERVICEFACTORY = 'ServiceDAOFactory';
    const DAOMKTPLACEFACTORY = 'MarketPlaceDAOFactory';
    const DAOTOPICSFACTORY =  'TopicsDAOFactory';
    
    /*public function getServiceListingDAOFactory() {
        return new ServiceListingDAOFactory();
    }*/
    
    public function getDAOFactory($factoryType) {
        switch ($factoryType) {
            case DAOFactory::DAOSERVICEFACTORY:
                $factoryObj = new ServiceListingDAOFactory();
                $factoryObj->getServiceListingDAO('mysql');
            case DAOFactory::DAOMKTPLACEFACTORY:
                echo "Code here";
            case DAOFactory::DAOTOPICSFACTORY:
                echo "Code here";
        }
    }
}
