<?php
	include 'database.php';
	include 'functions.php';
	session_start(); //Start session
	//Users must be logged in as an admin or business account in order to manage an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	}	
	
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` = '".$_POST["competitionID"]."' AND `accountID` = '".$_SESSION["accountDetails"]["accountID"]."'");
	$count = mysqli_num_rows($result); //Count the number of matches
	if ($count) {
		if (strlen($_POST["emailContents"])>2000) {
			header("Location: error.php?e=4");
		} else {
			$row = mysqli_fetch_assoc($result); //Turn entry data into array 
			$emailID = uniqid();

			$winner_result = $mysqli -> query('SELECT * FROM tblentry WHERE `entryID` ="'.$_POST["entryID"].'"');
			$winner = mysqli_fetch_assoc($winner_result); //Turn winning entry data into array 
			
			$result = $mysqli -> query("INSERT INTO `tblemail` (`emailID`,`senderID`,`targetID`,`emailContents`) VALUES ('".$emailID."','".$_SESSION["accountDetails"]["accountID"]."','".$winner["accountID"]."','".mysql_real_escape_string($_POST["emailContents"])."')"); //Add new entry to entries table using given values
			header ('Location: manage_event.php?event='.$_SESSION["competitionID"].'&m');
		}
		
	} else {
		header("Location: error.php?e=2");		
	}
?>