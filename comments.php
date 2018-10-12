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
include('/factory/CommentFactory.php');
        
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
        if ($_POST['submit'] == 'Create Comment' || $_POST['submit'] == 'Edit Comment') {                
            $loginId = $_SESSION['loginId'];
            $postId = $_SESSION['postId'];                
            $postComment = $_POST['comment'];

            if ($_POST['submit'] == 'Create Comment') {
                $action = "createComment";                
            }
            if ($_POST['submit'] == 'Edit Comment') {
                $id = $_POST['id'];                
                $action = "editComment";                
            }

            //Post build and create
            $comment = CommentFactory::create($loginId, $postId, $postComment);
            if ($_POST['submit'] == 'Edit Comment') {
                $comment->id = $id;
            }

            //CRUD at DB level
            RestController::handleRestCall($action, json_encode($comment));
        }
        if ($_POST['submit'] == 'Delete') {
            $action = "deleteComment";
            RestController::handleRestCall($action, json_encode($_POST['cb']));
        }        
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $postId = $_GET['postId'];
    $title = $_GET['title'];
    $user = $_GET['user'];
    $description = $_GET['description'];
    
    $_SESSION['postId'] = $postId;
    $_SESSION['title'] = $title;
    $_SESSION['user'] = $user;
    $_SESSION['description'] = $description;
}

if (isset($_POST['submit']) && $_POST['submit'] == 'Edit') {
    $id = $_POST['cb'][0];
    $tempComments = $_SESSION['comments'];
    foreach ($tempComments as $comment) {
        if ($comment['id'] == $id) {
            $commentDescription = $comment['comment'];
        }
    }
    
    print "<form action='comments.php' method='post' class='form--inline'>"; 
    print "<h4>Edit a Comment!</h4>";
    print "<p><label for=title>Post: " . $_SESSION['title'] . " / " .$_SESSION['description'] . "</p>";
    print "<p><textarea rows='4' cols='50' name='comment' value='" . $commentDescription . "'>" 
            . $commentDescription . "</textarea></p>";
    print "<input type='hidden' name='id' value='" . $id . "'>";
    print "<p><input type='submit' name='submit' value='Edit Comment' class='button--pill'></p>";
    print "</form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SESSION['NewEntry']) {
    print "<form action='comments.php?postId=$_SESSION[postId]&title=$_SESSION[title]"
            . "&description=$_SESSION[description]&user=$_SESSION[user]' method='post' class='form--inline'>"; 
    print "<h4>Comment on topic: " .$_SESSION['title'] . "</h4>";
    print "<h2>Topic Creator " .$_SESSION['user'] . " Says: " .$_SESSION['description'] . "</h2>";
    print "<p><label for=comment></label><textarea rows='4' cols='50' name='comment'></textarea></p>";
    print "<p><input type='submit' name='submit' value='Create Comment' class='button--pill'></p>";
    print "</form>";

    $result = mysql_query("select c.id as id, u.username as username, c.comment as comment, c.createdate as createdate"
            . " from comments c, users u "
            . "where c.PostId = '$_SESSION[postId]' and c.loginid = u.id");
    if (mysql_num_rows($result) == 0) {
        echo '<p class="text--error">There are no comments for this post. Be the first to create one!</p>';
    } else {
        //store results in session
        $comments = array();
        if (mysql_num_rows($result) != 0) { 
            while ($row = mysql_fetch_array($result)) {  
                $comments[] = $row;
            }
            $_SESSION['comments'] = $comments;
        }
    } 
}

//form to display comments for the selected post
print "<form action='comments.php' method='post' class='form--inline'>"; 
print "<p class='text--success'>$_SESSION[CommunityId]: $_SESSION[CommunityName]</p><br>";

if (isset($_SESSION['comments'])) {
    $tempComments = $_SESSION['comments'];
    echo "<div style='overflow-y:auto;'>";
    echo "<table>";
    echo "<tr><th>Creator</th><th>Comment</th><th>Created On</th>"; 
    if (isset($_SESSION['LOGIN'])) {
        echo "<th></th>";
    }
    echo "</tr>";
    
    foreach ($tempComments as $comments) {
        echo "<tr>"; 
        echo "<td>".$comments['username']."</td>";
        echo "<td>".$comments['comment']."</a></td>"; 
        echo "<td>".$comments['createdate']."</td>";                                
        if (isset($_SESSION['LOGIN'])) {
            echo "<td><input type=checkbox name=cb[] value=" .$comments['id']. "></td>";
        }
        echo "</tr>";
    }
    if (isset($_SESSION['LOGIN'])) {
        echo "<tr><td colspan='4' style='text-align: center'>"
        . "<input type='submit' name='submit' value='Edit'>&nbsp;&nbsp;&nbsp;"
                . "<input type='submit' name='submit' value='Delete'></td></tr>";
    }
    echo "</table>"; 
    echo "</div>";
} /*else {
    echo '<p class="text--error">There are no comments for this post. Be the first to create one!</p>';
}*/

$dbObj->closeConnection();   

include('templates/footer.html');        
