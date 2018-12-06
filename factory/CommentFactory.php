<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommentFactory
 *
 * @author Kajal.Nanaiah
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/Comments/Comments.php';

class CommentFactory {
    //put your code here
    
    public static function create($loginId, $postId, $postComment) {
        $comment = new Comments();
        $comment->buildPost($loginId, $postId, $postComment);
        return $comment;
    }        
}