<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostingDAOImpl
 *
 * @author Kajal.Nanaiah
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/DataAccessObject.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/youtopia/dataaccess/DBConnect.php';
include('Posting.php');

class PostingDAOImpl implements DataAccessObject {
    //put your code here
    
    var $posting;
    var $recordCount = 0;
    
    //Connect to database
    private function getDBConnection() {
        $dbObj = new DBConnect();
        $dbObj->selectDB();
    }

    public function PostingDAOImpl() {
        $this->getDBConnection();
        //$this->findAll();
    }
   
    public function createPost($postingObj) {
        $post = (array)$postingObj;
        $posting = new Posting();
        
        if ($post['id'] != '') {
            $posting->setId($post['id']);
        }
        $posting->setLoginId($post['loginId']);
        $posting->setCommunityId($post['communityId']);
        $posting->setTitle($post['postTitle']);
        $posting->setDescription($post['postDescription']);
        $posting->setStatus($post['status']);
        $posting->setCreateDate($post['createDate']);
        
        return $posting;
    }
    
    protected function execute($query) {
        $res = mysql_query($query);
        
        $recordCount = mysql_num_rows($res);
        
        if($recordCount > 0) {
            for($i = 0; $i < mysql_num_rows($res); $i++) {
                $row = mysql_fetch_assoc($res);
                print json_encode((array)$row);
                $posting[$i] = new Posting();
                $posting[$i]->setId($row['PostId']);
                $posting[$i]->setCommunityId($row['CommunityId']);
                $posting[$i]->setLoginId($row['LoginId']);
                $posting[$i]->setTitle($row['Title']);                
                $posting[$i]->setDescription($row['Description']);
                $posting[$i]->setStatus($row['Status']);
                $posting[$i]->setCreateDate($row['CreateDate']);
                //print json_encode((array)($posting[$i]));
            }
        }
        //var_dump($posting);
        /*foreach ($posting as $post) {
                        print json_encode((array)$post);
        }*/
     
    }
        
    //Create and Update a Post
    public function save($post) {
        $affectedRows = 0;
        $postingDB = array();
        $posting = $this->createPost(json_decode($post), true);
         
        try {
        if($posting->getId() != '') {
            $postingDB[$this->recordCount] = $this->findById($posting->getId());
        }
        
        // If the query returned a row then update,
        // otherwise insert a new user.
        if(sizeof($postingDB) > 0) {
            $query = "UPDATE posting SET ".
                "title='".$posting->getTitle()."', ".                    
                "description='".$posting->getDescription()."', ".
                "status='".$posting->getStatus()."' ".
                "WHERE postid=".$posting->getId();
            
            mysql_query($query);
            $affectedRows = mysql_affected_rows();
        }
        else {
            $query = "INSERT INTO posting (LoginId, CommunityId, Title, Description, Status, CreateDate) VALUES('".
                $posting->getLoginId()."', '".
                $posting->getCommunityId()."', '".
                $posting->getTitle()."', '".                    
                $posting->getDescription()."', '".
                $posting->getStatus()."', '". 
                $posting->getCreateDate()."')";                    
            
            mysql_query($query);
            $affectedRows = mysql_affected_rows();
        }} catch (Exception $e) {
            $e->getMessage();
            $e->getTrace();
        }
        return $affectedRows;
    }

    //Delete a users selected listing(s)
    public function delete($Id) {
        try {        
            /*$query = "delete posting, comments from posting inner join comments where "
                    . "posting.postid = comments.postid and posting.PostId in "
                    . "(" . implode(',',array_values(json_decode($Id))) . " and comments.postid in "
                    . "(" . implode(',',array_values(json_decode($Id))) . ")";*/
            $query1 = "delete from posting where postid in (" . implode(',',array_values(json_decode($Id))) . ")";
            $query2 = "delete from comments where postid (in " . implode(',',array_values(json_decode($Id))) . ")";
            // Before executing query2, determine if comments exist for the post
            mysql_query($query2);
            mysql_query($query1);            
        } catch (Exception $e) {
            $e->getMessage();
            $e->getTrace();
        }
    }
    
    //Retrieve all listings
    public function findAll() {
        $query = "select * from posting";
        return $this->execute($query);
    }
    
    //Retrieve a listing by its unique ID
    public function findById($Id) {
        $query = "select * from posting where postid = " .$Id;
        return $this->execute($query);
    }

    //Retrieve all listings for a community
    public function getPostsByCommunity($communityId) {
        $query = "select * from posting where communityId = " .$communityId;
        return $this->execute($query);
    }

    //Retrieve all lisitngs for a user
    public function getPostsByLogin($loginId) {
        $query = "select * from posting where loginId = " .$loginId;
        return $this->execute($query);
    }
    
    //Retrieve all listings within selected radius
    public function getPostsByRadius($radius, $communityId) {
        $query = "select * from posting where communityId = " .$communityId . " and radius = " . $radius;
        return $this->execute($query);
    }
}
