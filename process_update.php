<?php
	session_start(); //Start session

	if (!(isset($_SESSION["accountDetails"]))) { //If not logged in
		header("Location: error.php?e=3");
		exit();
	}	

	include 'database.php';
	
	$fullName=mysql_real_escape_string($_POST["fullname"]);
	$email=mysql_real_escape_string($_POST["email"]);
	$gender=mysql_real_escape_string($_POST["gender"]);
	
	$valid=true;
	$err_code="0";

	$result=$mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'"); //Find entries that match the given user ID
	$count=mysqli_num_rows($result); //Count the number of matches
	
	if (strlen($email)<4 || strlen($email)>30) {
		$valid=false;
	}
	else if (strlen($fullName)<3 || strlen($fullName)>40) {
		$valid=false;
	}
	else if (!(preg_match("/^[A-Za-z0-9 ]+$/i",$fullName))) {
		$valid=false;
	}
	else if($count>=1) {//If there is a match		
		$row=mysqli_fetch_assoc($result); //Turn user data into array 
		if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"])) { //If not logged in as the user being updated
			header("Location: error.php?e=1");
			exit();
		}
		$result=$mysqli -> query("UPDATE tblaccount SET accountEmail='".$email."', accountGender='".$gender."', accountName='".$fullName."' WHERE `accountID`='".$_SESSION["accountDetails"]["accountID"]."'"); //Update recorded user data
		$result=$mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'"); //Get new user data from table to update session
		$row=mysqli_fetch_assoc($result); //Turn updated user data into array 
		$_SESSION["accountDetails"]=$row; //Store account details as session value
		header('location:profile.php'); //Return to profile page
		$valid=true;
	} 
	else {
		$valid=false;
	}
	
	if ($valid==true) {
		header ('Location:profile.php');
	}
	else {
		header ('Location:profile.php?e='.$err_code);
	}
?>