<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBConnect
 *
 * @author dhigley
 */
class DBConnect {
    //put your code here
    var $link;
        
    function getDBConnection() {
        $this->link = mysql_connect('localhost', 'youtopia_guest', 'testnow');
        if (!$this->link) {
            die('Could not connect: ' . mysql_error());
        }  
        return $this->link;
    }

    function selectDB() {
        $link = $this->getDBConnection();
        $db_selected = mysql_select_db('youtopia', $link);  
        if (!$db_selected) {  
            die ('test not selected : ' . mysql_error());  
        } 
    }
    
    function closeConnection() {
        mysql_close($this->link);
    }
}
