<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceFactory
 *
 * @author dhigley
 */
class ServiceFactory {
    //put your code here
    
    public static function create($serviceCode, $serviceDescription, $loginId, $communityId) {
        $service = new Service();
        $service->buildService($serviceCode, $serviceDescription, $loginId, $communityId);
        return $service;
    }
}
