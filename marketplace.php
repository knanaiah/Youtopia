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
include('/factory/MarketPlaceItemFactory.php');
        
//Connect to database
$dbObj = new DBConnect();
$dbObj->selectDB();

const TARGET_DIR = "uploads/";
    
//if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uploadImg'])) {

function uploadFile() {
    // Add Code to display image on web page / store in DB
    $target_file = TARGET_DIR . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST['submit'])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mpitem'])) {
    //Check if user has logged in before a CRUD operation
    if (!isset($_SESSION['LOGIN'])) {
        //redirect to login page
        $loginStatus = 'Not_Logged_In';
        $_SESSION['url'] = $_SERVER['REQUEST_URI'];
        ob_start();
        header('Location: login.php?loginStatus='.$loginStatus); 
        ob_end_flush();                
    } else {
        if ($_POST['mpitem'] == 'List Item' || $_POST['mpitem'] == 'Edit Item') { // List item
            $loginId = $_SESSION['loginId'];
            $communityId = $_SESSION['CommunityId']; 
            $catId = $_POST['category'];
            $subcatId = $_POST['subcategory'];
            $price = $_POST['price'];
            $condition = $_POST['condition'];
            $description = $_POST['description'];
            
            if ($_POST['mpitem'] == 'List Item') {
                uploadFile();                
                $file = mysql_real_escape_string(file_get_contents($_FILES['fileToUpload']['tmp_name']));
                $filename = $_FILES['fileToUpload']['name'];
                $action = "createMarketPlaceItem";                
            }
            if ($_POST['mpitem'] == 'Edit Item') {
                $id = $_POST['id'];
                $file = "";
                $filename = "";
                $action = "editMarketPlaceItem";                
            }
            
            //Item build and create
            $marketplace = MarketPlaceItemFactory::create($catId, $subcatId, $price, $condition, 
                    $description, $file, $filename, $loginId, $communityId);
            //$marketplace->file = base64_encode($file);
            $mpResult = array(
                'catId' => $marketplace->catId,
                'subcatId' => $marketplace->subcatId,
                'price' => $marketplace->price,
                'condition' => $marketplace->condition,
                'description' => $marketplace->description,
                'file' => base64_encode($marketplace->file),
                'filename' => $marketplace->filename,            
                'loginId' => $marketplace->loginId,
                'communityId' => $marketplace->communityId,
                'status' => $marketplace->status,
                'createDate' => $marketplace->createDate
            );
            if ($_POST['mpitem'] == 'Edit Item') {
                $mpResult['id'] = $id;
            }
            
            //CRUD at DB level
            RestController::handleRestCall($action, json_encode($mpResult));
        }
        if ($_POST['mpitem'] == 'Delete') {
            $action = "deleteMarketPlaceItem";
            RestController::handleRestCall($action, json_encode($_POST['cb']));
        }        
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' || 
        ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mpitem']) && $_POST['mpitem'] != 'Edit')) {
    print "<form action='marketplace.php' method='post' autocomplete='off' "
        . "enctype='multipart/form-data' class='form--inline'>";
    print "<h4>Create a Market Place Listing!</h4>";
    print "<p>Category:&nbsp;&nbsp;<select name='category' onchange='this.form.submit()'>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['mpitem'])) {
        $idValue = strtok($_POST['category'], "_");
        $text = substr(strstr($_POST['category'], '_'), 1);
        print "<option value=" . $idValue. ">" . $text . "</option>";
        $categories = $_SESSION['categories'];
    } else {
        $result = mysql_query("select id, category from marketplacecategory");
        $categories = array();
        while ($row = mysql_fetch_array($result)) {
            $categories[] = $row;
        }
        $_SESSION['categories'] = $categories;
        print "<option disabled selected>Select Category</option>";    
    }

    foreach($categories as $item) {
        print "<option value='" . $item['id'] . "_" . $item['category'] ."'>" . $item['category'] ."</option>";            
    }

    print "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                . "Sub Category:&nbsp;&nbsp;<select name='subcategory'>";
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['category']) && !isset($_POST['mpitem'])) {
        $result = mysql_query("select id, category from marketplacesubcat where catid=" . $idValue);
        while ($row = mysql_fetch_array($result)) {
          print "<option value='" . $row['id'] . "'>" . $row['category'] . "</option>";
        }
    } else {
        print "<option disabled selected>Select Sub Category</option></td>";            
    }
    print "</select>";
    print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
    . "Price ($):&nbsp;&nbsp;<input type=text name=price size=20>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
            . "Condition:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
    . "<select name='condition'><option value='New'>New</option><option value='Used'>Used</option></select></p>";
    print "<br><br>";
    print "<p>Description:&nbsp;&nbsp;&nbsp;</label>"
    . "<textarea rows='3' cols='50' name='description'></textarea>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
            . "Select image to upload:<input type='file' name='fileToUpload' id='fileToUpload'></p>";
    print "<p><input type='submit' name='mpitem' value='List Item' class='button--pill'></p>";
    print "</form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['mpitem']) && $_POST['mpitem'] == 'Edit')) {
    $id = $_POST['cb'][0];
    $tempItems = $_SESSION['items'];
    foreach ($tempItems as $item) {
        if ($item['id'] == $id) {
            $price = $item['price'];
            $description = $item['description'];
            $category = $item['category'];
            $subcat = $item['subcat'];
            $condition = $item['itemcondition'];
            $imagename = $item['imagename'];
        }
    }
    
    print "<form action='marketplace.php' method='post' class='form--inline'>"; 
    print "<h4>Edit Your Market Place Item: " . $category . " / " . $subcat . "</h4>";
    print "<p>Price:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;"
    . "<input type='text' name='price' value='" . $price . "'></p>";        
    print "<p>Description:&nbsp;&nbsp;&nbsp<textarea rows='4' cols='50' name='description' value='" . $description . "'>" 
            . $description . "</textarea></p>";
    print "<input type='hidden' name='id' value='" . $id . "'>";
    print "<input type='hidden' name='category' value='" . $category . "'>";
    print "<input type='hidden' name='subcategory' value='" . $subcat . "'>";
    print "<input type='hidden' name='condition' value='" . $condition . "'>";    
    print "<input type='hidden' name='image' value='" . $imagename . "'>";        
    print "<p><input type='submit' name='mpitem' value='Edit Item' class='button--pill'></p>";
    print "</form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' || ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mpitem']))) {
    $selectQuery = "select u.username as username, c.category as category, s.category as subcat, m.id as id, "
            . "m.description as description, m.price as price, m.itemcondition as itemcondition, m.imagename as imagename, "
            . "m.createdate as createdate from marketplaceitems m, users u, marketplacecategory c, "
            . "marketplacesubcat s where communityid = '$_SESSION[CommunityId]' and m.loginid = u.id"
            . " and m.catid = c.id and m.subcatid = s.id";
    $result = mysql_query($selectQuery);
    if (mysql_num_rows($result) == 0) {
        echo '<p class="text--error">There are no marketplace items. Be the first to create one!</p>';
    } else {
        //store results in session
        $items = array();
        if (mysql_num_rows($result) != 0) { 
            while ($row = mysql_fetch_array($result)) {  
                $items[] = $row;
            }
            $_SESSION['items'] = $items;
        }
    } 
}

//form to display marketplace items for the selected community
print "<form action='marketplace.php' method='post' class='form--inline'>"; 
print "<p class='text--success'>$_SESSION[CommunityId]: $_SESSION[CommunityName]</p><br>";
        
if (isset($_SESSION['items'])) {
    $tempItems = $_SESSION['items'];
    echo "<div style='overflow-y:auto;'>";
    echo "<table>";
    echo "<tr><th>Creator</th><th>Item Type</th><th>Description</th><th>Price</th>"
        . "<th>Condition</th><th>Photo</th><th>Created On</th>";
    if (isset($_SESSION['LOGIN'])) {
        echo "<th></th>";
    }
    echo "</tr>"; 

    foreach ($tempItems as $items) {
        echo "<tr>"; 
        echo "<td>".$items['username']."</td>";
        //echo "<td>".$items['category']. " - " .$items['subcat']. "</td>";        
        echo "<td>".$items['subcat']. "</td>";                
        echo "<td>".$items['description']."</a></td>"; 
        echo "<td>\$".$items['price']."</a></td>"; 
        echo "<td>".$items['itemcondition']."</a></td>"; 
        echo "<td><img src='/youtopia/" . TARGET_DIR . ($items['imagename'])."' width='75' height='75'/></td>";         
        echo "<td>".$items['createdate']."</td>";                                
        if (isset($_SESSION['LOGIN'])) {
            echo "<td><input type=checkbox name=cb[] value=" .$items['id']. "></td>";
        }
        echo "</tr>";
    }
    if (isset($_SESSION['LOGIN'])) {
        echo "<tr><td colspan='8' style='text-align: center'>"
        . "<input type='submit' name='mpitem' value='Edit'>&nbsp;&nbsp;&nbsp;"
                . "<input type='submit' name='mpitem' value='Delete'></td></tr>";
    }
    echo "</table>"; 
    echo "</div>";
}
else {
    echo '<p class="text--error">There are no market place items for this community. Be the first to create one!</p>';
}
print "</form>"; 

$dbObj->closeConnection();   

include('templates/footer.html');        