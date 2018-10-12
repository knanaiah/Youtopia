<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostFactory
 *
 * @author dhigley
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/postings/Post.php';

class PostFactory {
    //put your code here
    
    public static function create($postTitle, $postDescription, $loginId, $communityId) {
        $post = new Post();
        $post->buildPost($postTitle, $postDescription, $loginId, $communityId);
        return $post;
    }    
}
