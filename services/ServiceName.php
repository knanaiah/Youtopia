<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceName
 *
 * @author Kajal.Nanaiah
 */
class ServiceName {
    //put your code here
    /*@const*/
    const CPOOL = 'Car Pool';    
    const CCARE = 'Child Care';
    const ECARE = 'Senior Care';
    const DCARE = 'Doggie Care';
    const BAASC = 'Before And After School Care';
    const CLEAN = 'Cleaning';
    const SHOPU = 'Shopping For You';
    const MEALP = 'Meal Preparation';
    const RIDES = 'Driving';
    const ACLAS = 'Art Classes';
    const MCALS = 'Music Classes';
    const HANDY = 'Handyman';
    const SWIMM = 'Swimming Lessons';

    public function getConstant($constantName) {
        return \constant('self::'.$constantName);
    }
}
