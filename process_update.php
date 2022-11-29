<?php

	$host = "localhost";
	$my_user = "danny";
	$my_password = "a";
	$my_db = "events_platform";
	$mysqli = new mysqli($host, $my_user, $my_password, $my_db); 	//Connect to the Database
	session_start(); //Start session
	
	$fullName = $_POST["fullname"];
	$email = $_POST["email"];
	$gender = $_POST["gender"];
	
	$valid=true;
	$err_code="0";


	$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'"); //Find entries that match the given user ID
	$count = mysqli_num_rows($result); //Count the number of matches
	
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
		$result = $mysqli -> query("UPDATE tblaccount SET accountEmail = '".$email."', accountGender = '".$gender."', accountName = '".$fullName."' WHERE `accountID` = ".$_SESSION["accountDetails"]["accountID"]); //Update recorded user data
		$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'"); //Get new user data from table to update session
		$row = mysqli_fetch_assoc($result); //Turn user data into array 
		$_SESSION["accountDetails"]=$row; //Store account details as session value
		header('location:profile.php');
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