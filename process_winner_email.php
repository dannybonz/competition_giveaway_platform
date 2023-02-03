<?php
	//This page is used when a business account submits a message for emailing to a winning participant. It takes the given input and records it in the emails table ready to be sent by the Node server as an email.
	include 'database.php';
	include 'functions.php';
	session_start(); //Start session
	
	//Users must be logged in as an admin or business account in order to manage an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1"); //Redirect to error page
		exit(); //Do not continue with rest of execution
	}	
	
	$result=$mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID`='".$_POST["competitionID"]."' AND `accountID`='".$_SESSION["accountDetails"]["accountID"]."'"); //Can only send emails to competitions managed by the logged in user
	$count=mysqli_num_rows($result); //Count the number of matches
	if ($count) { //If a match is found (i.e. there is a competition with the given ID and managed by the currently signed in user
		if (strlen($_POST["emailContents"])>2000) { //If the contents of the email exceeds the allocated length
			header("Location: error.php?e=4"); //Redirect to error page
		} else {
			$row=mysqli_fetch_assoc($result); //Turn competition data into array 
			$emailID=uniqid(); //Generate unique id for new record
			$winner_result=$mysqli -> query('SELECT * FROM tblentry WHERE `entryID`="'.$_POST["entryID"].'" AND `competitionID`="'.$_POST["competitionID"].'"'); //Find data of corresponding winning entry
			$winner=mysqli_fetch_assoc($winner_result); //Turn winning entry data into array
			$result=$mysqli -> query("INSERT INTO `tblemail` (`emailID`,`senderID`,`targetID`,`emailContents`) VALUES ('".$emailID."','".$_SESSION["accountDetails"]["accountID"]."','".$winner["accountID"]."','".mysql_real_escape_string($_POST["emailContents"])."')"); //Add new entry to email table, this will be read by the Node server
			header ('Location: manage_event.php?event='.$_SESSION["competitionID"].'&m'); //Redirect back to management page
		}
	} else {
		header("Location: error.php?e=2"); //If no match is found, redirect to error page
	}
?>