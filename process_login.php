<?php

	$host = "localhost";
	$my_user = "danny";
	$my_password = "a";
	$my_db = "events_platform";
	$mysqli = new mysqli($host, $my_user, $my_password, $my_db); 	//Connect to the Database

	$username = $_POST["username"]; //Get the submitted username
	$password = $_POST["password"]; //Get the submitted password
	$salt="1240813048"; //Use salt to encrypt password
	$password = md5($password.$salt);

	$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountUsername` ='".$username."' AND `accountPassword` = '".$password."'"); //Select matching combination from user table
	$count = mysqli_num_rows($result); //Count the number of matches
	echo "<br>";
	
	if($count>=1) //If there is a match
	{
		$row = mysqli_fetch_assoc($result); //Turn user data into array 
		session_start(); //Start session
		$_SESSION['loggedIn'] = "true"; //Set loggedin session value to true
		$_SESSION['accountID'] = $row["accountID"]; //Store user ID as a session value 
		$_SESSION['accountName'] = $row["accountName"]; //Store account name as a session value 
		$_SESSION['accountType'] = $row["accountType"]; //Store account type as a session value 
		$_SESSION["accountDetails"]=$row;
		header('location:index.php');
	}
	else //If there is not 1 match, i.e. incorrect combination
	{
		header('location:index.php?e2');
	}
?>