<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClubName
 *
 * @author Kajal.Nanaiah
 */
class ClubName {
    //put your code here
    /*@const*/
    const RUNNG = 'Lets Run';
    const UWALK = 'Lets Walk';
    const UBIKE = 'Lets Bike';
    const USWIM = 'Lets Swim';
    const TNNIS = 'Play Tennis';
    const VOLLY = 'Play VolleyBall';
    const INVST = 'Investing';
    const BOOKW = 'Book Worm';
    const YOGAA = 'Relax and Get Fit';
    const TAICH = 'Relax and Move with the Flow';
    
    public function getConstant($constantName) {
        return \constant('self::'.$constantName);
    }
}
