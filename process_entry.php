<?php
	include 'database.php';
	session_start(); //Start session
	if (!(isset($_SESSION["accountDetails"]))) { //If not logged in
		header("Location: error.php?e=3");
		exit();
	}	
	$entryID = uniqid();
	$result = $mysqli -> query("INSERT INTO `tblentry` (`entryID`,`accountID`,`competitionID`,`entryDate`,`entryFilepath`,`entryTextbox`) VALUES ('".$entryID."','".$_SESSION["accountDetails"]["accountID"]."','".$_SESSION["competitionID"]."',now(),'','".mysql_real_escape_string($_POST["text"])."')"); //Add new entry to entries table using given values
	header ('Location: view_event.php?event='.$_SESSION["competitionID"])
?>