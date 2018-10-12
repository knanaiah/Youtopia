<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
$_SESSION['NewEntry'] = false;
        
include('templates/header1.html');
include('/dataaccess/DBConnect.php');
include('/restful/RestController.php');  
include('/factory/PostFactory.php');
        
//Connect to database
$dbObj = new DBConnect();
$dbObj->selectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Check if user has logged in before a CRUD operation
    if (!isset($_SESSION['LOGIN'])) {
        //redirect to login page
        $loginStatus = 'Not_Logged_In';
        $_SESSION['url'] = $_SERVER['REQUEST_URI'];
        ob_start();
        header('Location: login.php?loginStatus='.$loginStatus); 
        ob_end_flush();                
    } else {
        if ($_POST['submit'] == 'Create Post' || $_POST['submit'] == 'Edit Post') {        
            $loginId = $_SESSION['loginId'];
            $communityId = $_SESSION['CommunityId'];                
            $postTitle = $_POST['title'];
            $postDescription = $_POST['description'];

            if ($_POST['submit'] == 'Create Post') {
                $action = "createPost";                
            }
            if ($_POST['submit'] == 'Edit Post') {
                $id = $_POST['id'];
                $action = "editPost";                
            }
            //Post build and create
            $post = PostFactory::create($postTitle, $postDescription, $loginId, $communityId);
            if ($_POST['submit'] == 'Edit Post') {
                $post->id = $id;
            }
            
            //CRUD at DB level
            RestController::handleRestCall($action, json_encode($post));
        }
        if ($_POST['submit'] == 'Delete') {
            $action = "deletePost";
            RestController::handleRestCall($action, json_encode($_POST['cb']));
        }        
    }
}

if (isset($_POST['submit']) && $_POST['submit'] == 'Edit') {
    $id = $_POST['cb'][0];
    $tempPosts = $_SESSION['posts'];
    foreach ($tempPosts as $post) {
        if ($post['postId'] == $id) {
            $title = $post['title'];
            $postDescription = $post['description'];
        }
    }
    
    print "<form action='posting.php' method='post' class='form--inline'>"; 
    print "<h4>Edit a topic!</h4>";
    print "<p><label for=title>Title:</label><input type=text name=title value='" . $title .  "' size=20></p>";
    print "<p><textarea rows='4' cols='50' name='description' value='" . $postDescription . "'>" 
            . $postDescription . "</textarea></p>";
    print "<input type='hidden' name='id' value='" . $id . "'>";
    print "<p><input type='submit' name='submit' value='Edit Post' class='button--pill'></p>";
    print "</form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SESSION['NewEntry']) {
    print "<form action='posting.php' method='post' class='form--inline'>"; 
    print "<h4>Create a topic!</h4>";
    print "<p><label for=title>Title:</label><input type=text name=title size=20></p>";
    print "<p><textarea rows='4' cols='50' name='description'></textarea></p>";
    print "<p><input type='submit' name='submit' value='Create Post' class='button--pill'></p>";
    print "</form>";
        
    $result = mysql_query("select u.username as username, p.postid as postId, p.title as title, "
            . "p.description as description, p.createdate as createdate from posting p, users u"
            . " where CommunityId = '$_SESSION[CommunityId]' and p.loginid = u.id");
    if (mysql_num_rows($result) == 0) {
        echo '<p class="text--error">There are no posts for this community. Be the first to create one!</p>';
    } else {
        //store results in session
        $posts = array();
        if (mysql_num_rows($result) != 0) { 
            while ($row = mysql_fetch_array($result)) {  
                $posts[] = $row;
            }
            $_SESSION['posts'] = $posts;
        }
    } 
}

//form to display posts for the selected community
print "<form action='posting.php' method='post' class='form--inline'>"; 
print "<p class='text--success'>$_SESSION[CommunityId]: $_SESSION[CommunityName]</p><br>";
        
if (isset($_SESSION['posts'])) {
    $tempPosts = $_SESSION['posts'];
    echo "<div style='overflow-y:auto;'>";
    echo "<table>";
    echo "<tr><th>Creator</th><th>Title</th><th>Description</th><th>Created On</th>";
    if (isset($_SESSION['LOGIN'])) {
        echo "<th></th>";
    }

    echo "</tr>"; 

    foreach ($tempPosts as $posts) {
        echo "<tr>"; 
        echo "<td>".$posts['username']."</td>";
        echo "<td><a href=comments.php?postId=" .$posts['postId']. 
                "&title=" .urlencode($posts['title']).  
                "&description=" .urlencode($posts['description']). 
                "&user=" .urlencode($posts['username']). 
                ">".$posts['title']."</a></td>"; 
        echo "<td>".$posts['description']."</td>";                                
        echo "<td>".$posts['createdate']."</td>";                                        
        if (isset($_SESSION['LOGIN'])) {
            echo "<td><input type=checkbox name=cb[] value=" .$posts['postId']. "></td>";
        }
        echo "</tr>";
    }
    if (isset($_SESSION['LOGIN'])) {
        echo "<tr><td colspan='5' style='text-align: center'>"
        . "<input type='submit' name='submit' value='Edit'>&nbsp;&nbsp;&nbsp;"
                . "<input type='submit' name='submit' value='Delete'></td></tr>";
    }
    echo "</table>"; 
    echo "</div>";
} /*else {
    echo '<p class="text--error">There are no posts for this community. Be the first to create one!</p>';
}*/
print "</form>"; 

$dbObj->closeConnection();   

include('templates/footer.html');        