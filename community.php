<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Uses header and footer templates to create layout.
session_start();
//This is set to true on a CRUD operation
$_SESSION['NewEntry'] = false;
        
include('templates/header1.html');
include('/dataaccess/DBConnect.php');
include('/services/Service.php');        
include('/restful/RestController.php');    
include('/factory/ServiceFactory.php');
include('clubs/ClubName.php');
        
//Connect to database
$dbObj = new DBConnect();
$dbObj->selectDB();
        
print "<p class='text--success'>Explore and Create - Topics, Services, Clubs and Market Place Offerings</p><br>";        
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['sendmail'])) {
    $result = mysql_query("select emailaddress from users where username='" .$_GET['user'] . "'");
    while ($row = mysql_fetch_array($result)) {
        $to =  $row['emailaddress']; 
    }
    $subject = $_GET['subject']; 
    $message = "I am interested in your service. Please contact me at " . $to;
    $headers = 'From: owneryoutopia@gmail.com' . "\r\n" .
                    'Reply-To: owneryoutopia@gmail.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();            
    mail($to, $subject, $message, $headers);
}

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
        if ($_POST['submit'] == 'Create Service' || $_POST['submit'] == 'Edit Service') {
            $loginId = $_SESSION['loginId'];
            $communityId = $_SESSION['CommunityId'];
            $serviceDescription = $_POST['description'];                
                
            if ($_POST['submit'] == 'Create Service') {
                $serviceCode = $_SESSION['serviceCode'];
                $action = "createService";                
            }
            if ($_POST['submit'] == 'Edit Service') {
                $serviceCode = $_POST['code'];
                $id = $_POST['id'];
                $action = "editService";                
            }
            //Service build and create
            $service = ServiceFactory::create($serviceCode, $serviceDescription, $loginId, $communityId);
            if ($_POST['submit'] == 'Edit Service') {
                $service->id = $id;
            }
            //CRUD at DB level
            RestController::handleRestCall($action, json_encode($service));
        }
        if ($_POST['submit'] == 'Delete') {
            $action = "deleteService";
            RestController::handleRestCall($action, json_encode($_POST['cb']));            
        }        
    }
}
        
//Display form for Creating New Services, marketplace items, posts
//This section should also display on redirection from login page
//if ((!empty($_GET['serviceCode']) || !empty($_SESSION['serviceCode'])) && isset($_POST['submit'])) {
if (isset($_POST['submit']) && $_POST['submit'] == 'Create Service') {
    $serviceName = ServiceName::getConstant((String)strtoupper($_GET['serviceCode']));
    $_SESSION['serviceCode'] = $_GET['serviceCode'];
    print "<form action='community.php' method='post' class='form--inline'>"; 
    print "<h4>Tell Us About Your " . $serviceName . " Service!</h4>";
    print "<p><textarea rows='4' cols='50' name='description'></textarea></p>";
    print "<p><input type='submit' name='submit' value='Create Service' class='button--pill'></p>";
    print "</form>";
}

//if (!empty($_SESSION['serviceCode']) && $_POST['submit'] == 'Edit') {
if (isset($_POST['submit']) && $_POST['submit'] == 'Edit') {
    $id = $_POST['cb'][0];
    $tempServices = $_SESSION['services'];
    foreach ($tempServices as $svcs) {
        if ($svcs['id'] == $id) {
            $serviceCode = $svcs['code'];
            $serviceDescription = $svcs['description'];
        }
    }
    
    //$serviceName = ServiceName::getConstant((String)strtoupper($_GET['serviceCode']));
    //$_SESSION['serviceCode'] = $_GET['serviceCode'];
    print "<form action='community.php' method='post' class='form--inline'>"; 
    print "<h4>Edit Your Service!</h4>";
    print "<p><textarea rows='4' cols='50' name='description' value='" . $serviceDescription . "'>" 
            . $serviceDescription . "</textarea></p>";
    print "<input type='hidden' name='id' value='" . $id . "'>";
    print "<input type='hidden' name='code' value='" . $serviceCode . "'>";    
    print "<p><input type='submit' name='submit' value='Edit Service' class='button--pill'></p>";
    print "</form>";
}

//Display form for Creating New or Editing clubs
//This section should also display on redirection from login page
if (!empty($_GET['clubCode']) || !empty($_SESSION['clubCode'])) {
    $clubName = ClubName::getConstant((String)strtoupper($_GET['clubCode']));
    $_SESSION['serviceCode'] = $_GET['clubCode'];
    print "<form action='community.php' method='post' class='form--inline'>"; 
    print "<h4>Tell Us About Your " . $clubName . " Service!</h4>";
    print "<p><textarea rows='4' cols='50' name='description'></textarea></p>";
    print "<p><input type='submit' name='submit' value='Create New' class='button--pill'></p>";
    print "</form>";
}
        
//Retrieve all services for selected community.
if (!empty($_GET['CommunityName']) || $_SESSION['NewEntry']) {
    if (!empty($_GET['CommunityName'])) {
        $_SESSION['CommunityId'] = $_GET['CommunityId'];
        $_SESSION['CommunityName'] = $_GET['CommunityName'];
    }
            
    //This must be a rest call. Not direct call to DB. Or call to DAO (Service Listing DAO)
    $result = mysql_query("SELECT sl.id, u.username, s.name, sl.description, sl.createdate, s.code "
                . "FROM servicelisting sl, services s, users u "
                . "where sl.CommunityId = '$_SESSION[CommunityId]' and s.Id = sl.SvcId "
                . "and sl.LoginId = u.Id and sl.status='ACTIVE'");
    $services = array();
    if (mysql_num_rows($result) != 0) { 
        while ($row = mysql_fetch_array($result)) {  
            $services[] = $row;
        }
        $_SESSION['services'] = $services;
    }
} else {
    $communityId = $_SESSION['CommunityId'];
    $communityName = $_SESSION['CommunityName'];
    $services = $_SESSION['services'];
}

//form to display services etc. for the selected community
print "<form action='community.php' method='post' class='form--inline'>"; 
print "<p class='text--success'>$_SESSION[CommunityId]: $_SESSION[CommunityName]</p><br>";
        
//if(mysql_num_rows($result) > 0) {
if (isset($_SESSION['services'])) {
    $tempServices = $_SESSION['services'];
    echo "<div style='overflow-y:auto;'>";
    echo "<table>";
    echo "<tr><th>Creator</th><th>Service</th><th>Description</th><th>Created On</th><th></th></tr>";

    foreach ($tempServices as $svcs) {
        echo "<tr>"; 
        echo "<td>".$svcs['username']."</td>";
        echo "<td>".$svcs['name']."</td>";                                        
        echo "<td><a href=community.php?sendmail=yes&user=".$svcs['username'].""
                        . "&subject=".urlencode($svcs['name']). 
                        "><div class=tooltip>".$svcs['description'].""
                        . "<span class=tooltiptext>Click to send inquiry</span></div></a></td>";
        echo "<td>".$svcs['createdate']."</td>"; 
        if (isset($_SESSION['LOGIN'])) {
            echo "<td><input type=checkbox name=cb[] value=" .$svcs['id']. "></td>";
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
} else {
    print '<p class="text--error">There are no services listed for this community.</p>';
}
print "</form>"; 
            
$dbObj->closeConnection();        

include('templates/footer.html');        

        
        
        