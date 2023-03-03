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
	$_SESSION=array("accountDetails" => array("accountType" => "Admin", "accountID" => "test")); //Enable admin privileges

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
	$newly_registered_user=$accountID;
	if($count>=1) { //If the account exists 
		echo "<br>Check: User exists";
		echo '<div class="success">Success</div>';
	} else {
		echo "<br>Check: User does not exist";
		echo '<div class="failure">Failure</div>';		
	}
	echo '</div>';

	//Test 2: Delete user account
	$_POST["user"]=$newly_registered_user;
	echo '<div class="test"><h3>#2 Delete User Account</h1><br>Response: ';
	include 'process_user_delete.php';
	$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountUsername` ='TestUser'");
	$count = mysqli_num_rows($result);
	if($count>=1) { //If the account exists 
		echo "<br>Check: User still exists";
		echo '<div class="failure">Failure</div>';		
	} else {
		echo "<br>Check: User does not exist";
		echo '<div class="success">Success</div>';
	}
	echo '</div>';

	//Test 3: Create event
	$_POST["title"]="TestEvent";
	$_POST["description"]="TestDescription";
	$_POST["rules"]="TestRules";
	$_POST["deadline"]="2025-01-01";
	$_POST["start"]="2000-01-01";
	$_POST["event"]="0";
	$_POST["win"]="random";
	$_POST["text"]="50";
	$_POST["winners"]="2";
	echo '<div class="test"><h3>#3 Create Event</h1><br>Response: ';
	include 'process_event_create.php';
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionTitle` ='TestEvent'");
	$count = mysqli_num_rows($result);
	if($count>=1) { //If the event exists 
		echo "<br>Check: Event exists";
		echo '<div class="success">Success</div>';
	} else {
		echo "<br>Check: Event does not exist";
		echo '<div class="failure">Failure</div>';		
	}
	echo '</div>';

	//Test 4: Edit event
	$_GET["event"]=$competitionID;
	$_POST["event"]=$competitionID;
	$_POST["title"]="NewTitledTestEvent";
	echo '<div class="test"><h3>#4 Edit Event</h1><br>Response: ';
	include 'process_event_create.php';
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionTitle` ='NewTitledTestEvent'");
	$count = mysqli_num_rows($result);
	if($count>=1) { //If event with new title exists 
		echo "<br>Check: Event exists with new title";
		echo '<div class="success">Success</div>';
	} else {
		echo "<br>Check: Event does not exist with new title";		
		echo '<div class="failure">Failure</div>';		
	}
	echo '</div>';

	//Test 5: Delete event
	echo '<div class="test"><h3>#5 Delete Event</h1><br>Response: ';
	include 'process_event_delete.php';
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionTitle` ='TestEvent'");
	$count = mysqli_num_rows($result);
	$newly_registered_user=$accountID;
	if($count>=1) { //If the event exists 
		echo "<br>Check: Event still exists";
		echo '<div class="failure">Failure</div>';		
	} else {
		echo "<br>Check: Event does not exist";
		echo '<div class="success">Success</div>';
	}
	echo '</div>';
?>