<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceType
 *
 * @author Kajal.Nanaiah
 */
class ServiceType {
    //put your code here
    
    const CCARE = "Service";
    const DCARE = "Service";
    const ECARE = "Service";
    const BAASC = "Service";
    const CLEAN = "Service";
    const CPOOL = "Service";
    const RIDES = "Service";
    const MEALP = "Service";
    const SHOPU = "Service";
    const ACLAS = "Service";
    const MCLAS = "Service";
    const HANDY = "Service";
    
    const RUNNG = "Club";
    const UWALK = "Club";
    const UBIKE = "Club";
    const USWIM = "Club";
    const TNNIS = "Club";
    const VOLLY = "Club";
    const INVST = "Club";
    const BOOKW = "Club";
    const YOGAA = "Club";
    const TAICH = "Club";
    
    public function getConstant($constantName) {
        return constant('self::'.$constantName);
    }
}
