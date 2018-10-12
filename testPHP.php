<?php

include('/dataaccess/services/ServiceListingDAOImpl.php');
include('/factory/DAOFactory.php');

/* Calling a PHP Function */
    if(isset($_GET['writeMessage']) && $_GET['writeMessage'] == 1){
        //$obj = new ServiceListingDAOImpl();
        $obj = new DAOFactory();
        $obj->getDAOFactory(DAOFactory::DAOSERVICEFACTORY);
        //print DAOFactory::DAOSERVICEFACTORY;
        //$obj->Test_createService();
    }
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

