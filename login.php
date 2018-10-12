<?php // Script 8.8 - login.php
session_start(); 
/* This page lets people log into the site (in theory). */
// Set the page title and include the header file
define('TITLE', 'Login');
include('templates/header.html');
include('/dataaccess/DBConnect.php');

if(isset($_SESSION['url'])) {
   $url = $_SESSION['url']; // holds url for last page visited.
} else {
   $url = 'index.php'; 
}

if (!empty($_GET['regStatus']) && $_GET['regStatus'] == true) {
    print '<p class="text--success">You are now successfully registered! Please Login!</p>';
}
if (!empty($_GET['loginStatus']) && $_GET['loginStatus'] == 'Not_Logged_In') {
    print '<p class="text--success">You need to be logged in to take advantage of these features. If you are a new user, '
    . 'please <a href=register.php>Sign Up</a> first.</p>';
}

// Print some introductory text:
print '<h2>Login Form</h2>';

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Handle the form:
	if ( (!empty($_POST['email'])) && (!empty($_POST['password'])) ) {
            //Connect to database
            $dbObj = new DBConnect();
            $dbObj->selectDB();
            
            //$query = "select FirstName, LastName, PasswordEnc from users where EmailAddress = 'strtolower($_POST[email])'";
            $email = strtolower($_POST['email']);
            $query = "select Id, FirstName, LastName, PasswordEnc from users where EmailAddress = '".$email . "'";
            $result = mysql_query($query);
            if(mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_array($result)) {
                    if (password_verify($_POST['password'], $row['PasswordEnc'])) {
                        //print '<p class="text--success">You are logged in!</p>';
                        $_SESSION['LOGIN'] = 'Y';
                        $_SESSION['loginId'] = $row['Id'];
                        header('Location: ' .$url);
                    } else { // Incorrect!
                        $error = true;
                        print '<p class="text--error">The submitted credentials are invalid!<br>Please try again.</p>';
                    }
                }
            } else {
                $error = true;
                print '<p class="text--error">The email address you provided is not registered!<br>Please try again.</p>';
            }
            
            /*if ( (strtolower($_POST['email']) == 'me@example.com') && ($_POST['password'] == 'testpass') ) { // Correct!
		print '<p class="text--success">You are logged in!<br>Now you can blah, blah, blah...</p>';
            } else { // Incorrect!
		print '<p class="text--error">The submitted email address and password do not match those on file!<br>Go back and try again.</p>';
            }*/
	} else { // Forgot a field.
                $error = true;
		print '<p class="text--error">Please make sure you enter both an email address and a password!'
                . '<br>Please try again.</p>';
	}
    $dbObj->closeConnection();        
} 

if (($_SERVER['REQUEST_METHOD'] == 'POST' && $error) || $_SERVER['REQUEST_METHOD'] == 'GET') { // Display the form.
	print '<form action="login.php" method="post" class="form--inline">
	<p><label for="email">Email Address:</label><input type="email" name="email" size="20"></p>
	<p><label for="password">Password:</label><input type="password" name="password" size="20"></p>
	<p><input type="submit" name="submit" value="Log In!" class="button--pill"></p>
	</form>';
}
include('templates/footer.html'); // Need the footer.



