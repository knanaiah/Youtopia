<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataAccessObject
 *
 * @author Kajal.Nanaiah
 */
interface DataAccessObject {

    //put your code here
    //Create and Update Service Listing - By user for community
    public function save($obj);

    //Delete a users selected listing(s)
    public function delete($obj);
    
    public function findById($Id);
    
    public function findAll();

}
