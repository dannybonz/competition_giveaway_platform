<?php
	include 'database.php';
	
	$username = mysql_real_escape_string($_POST["username"]); //Get the submitted username
	$password = mysql_real_escape_string($_POST["password"]); //Get the submitted password
	$salt="dc0b2dd4f78221adac85386e9ee57a9047562d5"; //Use salt to encrypt password
	$password = md5($password.$salt);

	$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountUsername` ='".$username."' AND `accountPassword` = '".$password."'"); //Select matching combination from user table
	$count = mysqli_num_rows($result); //Count the number of matches
	
	if($count>=1) //If there is a match
	{
		$row = mysqli_fetch_assoc($result); //Turn user data into array 
		session_start(); //Start session
		$_SESSION['loggedIn'] = "true"; //Set loggedin session value to true
		$_SESSION["accountDetails"]=$row; //Store account details as session value
		if (isset($_POST["debug"])) {
			echo "Complete";
		} else {
			header('location:index.php');
		}
	}
	else //If there is not a match, i.e. incorrect combination
	{
		header('location:index.php?e2'); //Redirect to homepage with error message
	}
?>