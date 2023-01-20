<?php
	include 'header.php'; //Adds header to top of page and starts session
	include 'functions.php';

	//Users must be logged in as an admin or business account in order to manage an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	} else {
		include 'database.php';
		$result = $mysqli -> query("UPDATE `tblcompetition` SET `competitionWinningEntry` = '' WHERE `competitionID` = '".$_GET["event"]."' AND `accountID` = '".$_SESSION["accountDetails"]["accountID"]."'"); //Clear the winning entry field
		header ('Location: manage_event.php?event='.$_GET["event"]); //Redirect to relevant event management page
	}
?>