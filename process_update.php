<?php
	//This page is used when a user updates their profile information. It updates their record in the database with the newly provided information.
	session_start(); //Start session

	if (!(isset($_SESSION["accountDetails"]))) { //If not logged in
		header("Location: error.php?e=3"); //Redirect to error page
		exit();
	}	

	include 'database.php';
	
	$fullname=mysql_real_escape_string($_POST["fullname"]); //Escape string to prevent injection
	$email=mysql_real_escape_string($_POST["email"]);
	$gender=mysql_real_escape_string($_POST["gender"]);
	
	$valid=true;
	$err_code="0";

	$result=$mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'"); //Find matching user data
	$count=mysqli_num_rows($result); //Count the number of matches
	
	$email_result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountEmail` ='".$email."' AND `accountID` != '".$_SESSION["accountDetails"]["accountID"]."'"); //Find other entries that already use the given email address
	$email_count = mysqli_num_rows($email_result);
	if($email_count>=1) { //If the email has already been used before 
		$valid=false;
		$err_code="1";
	}
	else if (strlen($email)<4 || strlen($email)>30) { //If the provided email doesn't meet the character length limitations
		$valid=false;
	}
	else if (strlen($fullname)<3 || strlen($fullname)>40) { //If the provided full name doesn't meet the character length limitations
		$valid=false;
	}
	else if (!(preg_match("/^[A-Za-z0-9 ]+$/i",$fullname))) { //If forbidden characters are used in the full name
		$valid=false;
	}
	else if($count>=1) {//If there is a match		
		$result=$mysqli -> query("UPDATE tblaccount SET accountEmail='".$email."', accountGender='".$gender."', accountName='".$fullname."' WHERE `accountID`='".$_SESSION["accountDetails"]["accountID"]."'"); //Update recorded user data
		$result=$mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'"); //Get new user data from table to update session
		$row=mysqli_fetch_assoc($result); //Turn updated user data into array 
		$_SESSION["accountDetails"]=$row; //Store account details as session value
		header('location:profile.php'); //Return to profile page
		$valid=true;
	} 
	else {
		$valid=false;
	}
	
	if ($valid==true) { //If the provided information passed validation, direct back to profile page
		header ('Location:profile.php');
	}
	else { //If the provided information was invalid, direct to profile page with provided error code to display error message
		header ('Location:profile.php?e='.$err_code);
	}
?>