<html lang="en">
	<head>
		<title>Unit Tests</title>
		<link rel="stylesheet" href="style.css"> 
		<script src="functions.js"></script>
	</head>
</html>

<?php
	//This file includes all of the application's unit tests.
	session_start(); //Start session
	include 'database.php'; //Include database
	$_POST['debug'] = 'true'; //Enable debug to receive error information
	$_SESSION=array("accountDetails" => array("accountType" => "Admin")); //Enable admin privileges

	//Test 1: Create user account
	$_POST["username"]="TestUser";
	$_POST["password"]="TestPassword";
	$_POST["fullname"]="Test Account";
	$_POST["email"]="test@gmail.com";
	$_POST["birthday"]="2002-04-22";
	$_POST["gender"]="male";
	echo '<div class="test"><h3>#1 Create User Account</h1><br>Response: ';
	include 'process_register.php';
	$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountUsername` ='TestUser'");
	$count = mysqli_num_rows($result);
	if($count>=1) { //If the account exists 
		echo '<div class="success">Success</div>';
	} else {
		echo '<div class="failure">Failure</div>';		
	}
	echo '</div>';
	
	//Test 2: Delete user account
	$_POST["user"]=$accountID;
	echo '<div class="test"><h3>#2 Delete User Account</h1><br>Response: ';
	include 'process_user_delete.php';
	$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountUsername` ='TestUser'");
	$count = mysqli_num_rows($result);
	if($count>=1) { //If the account exists 
		echo '<div class="failure">Failure</div>';		
	} else {
		echo '<div class="success">Success</div>';
	}

	echo '</div>';

	

?>