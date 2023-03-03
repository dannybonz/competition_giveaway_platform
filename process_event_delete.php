<?php
	//This page is used when a business account chooses to delete a previously created event.
	session_start();
	//Users must be logged in as an admin or business account in order to delete an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1"); //If not logged in, redirect to error page
		exit(); //Cease execution of page
	}

	include 'database.php'; //Connect to database
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'"); //Find matching competition information
	$count = mysqli_num_rows($result); //Count the number of matches

	if($count<1) { //If there isn't a match
		header ("location:error.php?e=2"); //Redirect to error page
		exit(); //Cease execution of page
	}

	$row = mysqli_fetch_assoc($result); //Turn competition data into array 
	if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"] or $_SESSION["accountDetails"]["accountType"]=="Admin")) { //If you are not currently logged in as the creator of the competition or an admin user, you cannot delete it.
		header ("location:error.php?e=1"); //Redirect to error page
		exit(); //Cease execution of page
	}

	$result = $mysqli -> query("DELETE FROM `tblcompetition` WHERE `competitionID` = '".$_GET["event"]."'"); //Delete competition based on given ID
	$result = $mysqli -> query("DELETE FROM `tblentry` WHERE `competitionID` = '".$_GET["event"]."'"); //Delete any entries for the competition
	$result = $mysqli -> query("DELETE FROM `tblwinner` WHERE `competitionID` = '".$_GET["event"]."'"); //Delete any winners for the competition
	
	if (isset($_POST["debug"])) {
		echo "Complete";
	} else {
		header ('Location: event_management.php'); //Redirect back to event management page		
	}
?>