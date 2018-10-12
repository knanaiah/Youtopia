<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MarketPlaceItemFactory
 *
 * @author dhigley
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/marketplace/MarketPlaceItem.php';

class MarketPlaceItemFactory {
    //put your code here
    
    public static function create($catId, $subcatId, $price, $condition, $description, $file, $filename, $loginId, $communityId) {
        $marketplace = new MarketPlaceItem();
        $marketplace->buildMarketPlaceItem($catId, $subcatId, $price, $condition, $description, 
                $file, $filename, $loginId, $communityId);
        return $marketplace;
    }
}
