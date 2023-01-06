<?php

	$host = "localhost";
	$my_user = "danny";
	$my_password = "a";
	$my_db = "events_platform";
	$mysqli = new mysqli($host, $my_user, $my_password, $my_db); 	//Connect to the Database
	session_start(); //Start session
	
	$username = $_POST["username"];
	$password = $_POST["password"];
	$fullName = $_POST["fullname"];
	$email = $_POST["email"];
	$birthday = $_POST["birthday"];
	$gender = $_POST["gender"];
	
	$valid=true;
	$err_code="0";

	$result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountUsername` ='".$username."'"); //Find entries that match the given username 
	$count = mysqli_num_rows($result);
	if($count>=1) { //If the username has already been used before 
		$valid=false;
		$err_code="1";
	}
	else if (strlen($username)>10 || strlen($username)<1) {
		$valid=false;
		echo "one";
	}
	else if (!(preg_match("/^[A-Za-z0-9]+$/i",$username))){
		$valid=false;
		echo "2";
	}
	else if (strlen($password)<4 || strlen($password)>30) {
		$valid=false;
		echo "3";
	}
	else if (strlen($email)<4 || strlen($email)>30) {
		$valid=false;
		echo "4";
	}
	else if (strlen($fullName)<3 || strlen($fullName)>40) {
		$valid=false;
		echo "5";
	}
	else if (!(preg_match("/^[A-Za-z0-9 ]+$/i",$fullName))) {
		$valid=false;
		echo "6";
	}
	else {
		$now = new DateTime();
		if ($birthday > $now) {
			$valid=false;
			echo "7";
		}
	}//*/
	
	if ($valid==true) {
		$salt="dc0b2dd4f78221adac85386e9ee57a9047562d5"; //Salt used for encryption 
		$password = md5($password.$salt); //Encrypt with md5 
		$accountID = uniqid();
		$result = $mysqli -> query("INSERT INTO `tblaccount` (`accountID`,`accountName`,`accountUsername`,`accountType`,`accountPassword`,`accountGender`,`accountEmail`,`accountBirthday`) VALUES ('".$accountID."','".$fullName."','".$username."','Standard','".$password."','".$gender."','".$email."','".$birthday."')"); //Add new member to members table using given values
		header ('Location:index.php?registered');
	}
	else {
		header ('Location:index.php?e='.$err_code);
	}

?>