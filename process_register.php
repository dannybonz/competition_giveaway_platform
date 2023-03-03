<?php

	$host = "localhost";
	$my_user = "danny";
	$my_password = "a";
	$my_db = "events_platform";
	$mysqli = new mysqli($host, $my_user, $my_password, $my_db); //Connect to the Database
	session_start(); //Start session
	
	$username = mysql_real_escape_string($_POST["username"]);
	$password = mysql_real_escape_string($_POST["password"]);
	$fullName = mysql_real_escape_string($_POST["fullname"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$birthday = mysql_real_escape_string($_POST["birthday"]);
	$gender = mysql_real_escape_string($_POST["gender"]);
		
	$valid=true;
	$err_code="0";

	$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountEmail` ='".$email."'"); //Find entries that match the given username 
	$count = mysqli_num_rows($result);
	if($count>=1) { //If the email has already been used before 
		$valid=false;
		$err_code="2";
	} 
	else {
		$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountUsername` ='".$username."'"); //Find entries that match the given username 
		$count = mysqli_num_rows($result);
		if($count>=1) { //If the username has already been used before 
			$valid=false;
			$err_code="1";
		}
		else if (strlen($username)>10 || strlen($username)<1) {
			$valid=false;
		}
		else if (!(preg_match("/^[A-Za-z0-9]+$/i",$username))){
			$valid=false;
		}
		else if (strlen($password)<4 || strlen($password)>30) {
			$valid=false;
		}
		else if (strlen($email)<4 || strlen($email)>30) {
			$valid=false;
		}
		else if (strlen($fullName)<3 || strlen($fullName)>40) {
			$valid=false;
		}
		else if (!(preg_match("/^[A-Za-z0-9 ]+$/i",$fullName))) {
			$valid=false;
		}
		else {
			$now = new DateTime();
			if ($birthday > $now) {
				$valid=false;
			}
		}
	}
	
	$_POST["debug"]="true";
	if ($valid==true) {
		$salt="dc0b2dd4f78221adac85386e9ee57a9047562d5"; //Salt used for encryption 
		$password = md5($password.$salt); //Encrypt with md5 
		$accountID = uniqid();
		$result = $mysqli -> query("INSERT INTO `tblaccount` (`accountID`,`accountName`,`accountUsername`,`accountType`,`accountPassword`,`accountGender`,`accountEmail`,`accountBirthday`) VALUES ('".$accountID."','".$fullName."','".$username."','Standard','".$password."','".$gender."','".$email."','".$birthday."')"); //Add new member to members table using given values

		if ($_POST["debug"]=="true") {
			echo "Complete";
		} else {
			header ('Location:index.php?registered');
		}
	}
	else {
		if ($_POST["debug"]=="true") {
			echo "Error ".$err_code;
		} else {
			header ('Location:index.php?e='.$err_code);
		}
	}

?>