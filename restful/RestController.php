<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RestController
 *
 * @author dhigley
 */

class RestController {
    //put your code here
    
    const URL = "localhost/youtopia/restful/RestHandler.php";
    
    //CREATE A REST CONTROLLER TO HANDLE REQUESTS
    public static function handleRestCall($action, $json_object) {
        //set header type
        //header("Content-Type:application/json");
        
                $client = curl_init(RestController::URL);
                curl_setopt($client, CURLOPT_POSTFIELDS, 'action=' .$action . '&data=' .$json_object);
                //curl_setopt($client, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($client, CURLOPT_RETURNTRANSFER, true );
                curl_exec($client);
                if(curl_error($client)) {
                    echo 'error:' . curl_error($client);
                } else {
                    $_SESSION['NewEntry'] = true;
                }
                curl_close($client);
    }
}
