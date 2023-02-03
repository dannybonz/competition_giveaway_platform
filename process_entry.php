<?php
	//This page is used when users submit a new entry to a competition
	include 'database.php';
	session_start(); //Start session
	if (!(isset($_SESSION["accountDetails"]))) { //If not logged in
		header("Location: error.php?e=3"); //Redirect to error page
		exit(); //Cease execution of page
	}	
	$entryID = uniqid(); //Create unique ID for entry record
	$result = $mysqli -> query("INSERT INTO `tblentry` (`entryID`,`accountID`,`competitionID`,`entryDate`,`entryFilepath`,`entryTextbox`) VALUES ('".$entryID."','".$_SESSION["accountDetails"]["accountID"]."','".$_SESSION["competitionID"]."',now(),'','".mysql_real_escape_string($_POST["text"])."')"); //Add new entry to entries table using given values
	header ('Location: view_event.php?event='.$_SESSION["competitionID"]) //Redirect to relevant event page
?>