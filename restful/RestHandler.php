<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/services/Service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/postings/Post.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/Comments/Comments.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/MarketPlace/MarketPlaceItem.php';

echo "IN REST HANDLER!!!";

if ($_POST['action'] == 'createService' || $_POST['action'] == 'editService') {
    $service = new Service();
    $service->createService($_POST['data']);
}

if ($_POST['action'] == 'deleteService') {
    $service = new Service();
    $service->delete($_POST['data']);
}

if ($_POST['action'] == 'createPost' || $_POST['action'] == 'editPost') {
    $post = new Post();
    $post->createPost($_POST['data']);
}

if ($_POST['action'] == 'deletePost') {
    $post = new Post();
    $post->delete($_POST['data']);
}

if ($_POST['action'] == 'createComment' || $_POST['action'] == 'editComment') {
    $comment = new Comments();
    $comment->createComment($_POST['data']);
}

if ($_POST['action'] == 'deleteComment') {
    $comment = new Comments();
    $comment->delete($_POST['data']);
}

if ($_POST['action'] == 'createMarketPlaceItem' || $_POST['action'] == 'editMarketPlaceItem') {
    $marketplaceitem = new MarketPlaceItem();
    $marketplaceitem->createMarketPlaceItem($_POST['data']);
}

if ($_POST['action'] == 'deleteMarketPlaceItem') {
    $marketplaceitem = new MarketPlaceItem();
    $marketplaceitem->delete($_POST['data']);
}