<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommentDAOImpl
 *
 * @author dhigley
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/DataAccessObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/DBConnect.php';
include('Comment.php');

class CommentDAOImpl {
    //put your code here
    
    var $comment;
    var $recordCount = 0;
    
    //Connect to database
    private function getDBConnection() {
        $dbObj = new DBConnect();
        $dbObj->selectDB();
    }

    public function CommentDAOImpl() {
        $this->getDBConnection();
        //$this->findAll();
        //$this->findByPostId($postId);
    }
   
    public function createComment($commentObj) {
        $comment = (array)$commentObj;
        $postComment = new Comment();
        
        if ($comment['id'] != '') {
            $postComment->setId($comment['id']);
        }
        $postComment->setLoginId($comment['loginId']);
        $postComment->setPostId($comment['postId']);
        $postComment->setComment($comment['postComment']);
        $postComment->setStatus($comment['status']);
        $postComment->setCreateDate($comment['createDate']);
        
        return $postComment;
    }
    
    protected function execute($query) {
        $res = mysql_query($query);
        
        $recordCount = mysql_num_rows($res);
        
        if($recordCount > 0) {
            for($i = 0; $i < mysql_num_rows($res); $i++) {
                $row = mysql_fetch_assoc($res);
                print json_encode((array)$row);
                $comment[$i] = new Comment();
                $comment[$i]->setId($row['Id']);
                $comment[$i]->setLoginId($row['loginId']);
                $comment[$i]->setPostId($row['postId']);
                $comment[$i]->setComment($row['postComment']);
                $comment[$i]->setStatus($row['Status']);
                $comment[$i]->setCreateDate($row['CreateDate']);
                //print json_encode((array)($posting[$i]));
            }
        }
        //var_dump($posting);
        /*foreach ($posting as $post) {
                        print json_encode((array)$post);
        }*/
    }
        
    //Create Comment
    public function save($comment) {
        $affectedRows = 0;
        $commentDB = array();
        $postComment = $this->createComment(json_decode($comment), true);
         
        try {
            if($postComment->getId() != '') {
                $commentDB[$this->recordCount] = $this->findById($postComment->getId());
            }

            // If the query returned a row then update,
            // otherwise insert a new user.
            if(sizeof($commentDB) > 0) {
                $query = "UPDATE comments SET ".
                    "comment='".$postComment->getComment()."', ".
                    "status='".$postComment->getStatus()."' ".
                    "WHERE id=".$postComment->getId();

                mysql_query($query);
                $affectedRows = mysql_affected_rows();
            } else {
                $query = "INSERT INTO comments (LoginId, PostId, Comment, Status, CreateDate) VALUES('".
                $postComment->getLoginId()."', '".
                $postComment->getPostId()."', '".
                $postComment->getComment()."', '".
                $postComment->getStatus()."', '". 
                $postComment->getCreateDate()."')";       

                mysql_query($query);
                $affectedRows = mysql_affected_rows();
            }
        } catch (Exception $e) {
            $e->getMessage();
            $e->getTrace();
        }
        return $affectedRows;
    }

    //Delete a users selected comment
    public function delete($Id) {
        try {                
            $query = "delete from comments where Id in (" . implode(',',array_values(json_decode($Id))) . ")";
            mysql_query($query);
        } catch (Exception $e) {
            $e->getMessage();
            $e->getTrace();
        }
    }
    
    //Retrieve a comment by its unique ID
    public function findById($Id) {
        $query = "select * from comments where id = " .$Id;
        return $this->execute($query);
    }
    
    //Retrieve a comment by its post ID
    public function findByPostId($postId) {
        $query = "select * from comments where id = " .$postId;
        return $this->execute($query);
    }
}
