<?php
	//This page is used when users submit a new entry to a competition
	include 'database.php';
	session_start(); //Start session
	if (!(isset($_SESSION["accountDetails"]))) { //If not logged in
		header("Location: error.php?e=3"); //Redirect to error page
		exit(); //Cease execution of page
	}

	//Confirm competition exists and is during the period of entry
	$result=$mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_SESSION["competitionID"]."' AND `competitionStartDate` <= CURDATE() AND `competitionEndDate` > CURDATE()"); //Get competition info - ensure the competition has started and hasn't finished
	$count=mysqli_num_rows($result); //Count the number of matches	
	if ($count<1) { //If there isn't a match
		header ("location:error.php?e=2"); //Redirect to error page
		exit(); //Cease execution of page
	}
	$row=mysqli_fetch_assoc($result); //Turn event data into array
	
	//Confirm user hasn't already entered
	$result=$mysqli -> query("SELECT * FROM tblentry WHERE `accountID` ='".$_SESSION["accountDetails"]["accountID"]."' AND `competitionID` ='".$_SESSION["competitionID"]."'"); //Select an entry for this competition from the currently logged in user
	$count=mysqli_num_rows($result); //Count the number of matches	
	if ($count>0) { //If there is a match (user has already entered this event)
		header ("location:error.php?e=5"); //Redirect to error page
		exit(); //Cease execution of page
	}

	//Confirm entry matches requirements
	$text_req = $row["competitionTextRequirement"];
	if ((strlen($_POST["text"]>0) and $text_req=="None") or ($text_req!="None" and strlen($_POST["text"]>(int)$text_req))) {
		header ("location:error.php?e=4"); //Redirect to error page		
		exit(); //Cease execution of page
	}

	$entryID = uniqid(); //Create unique ID for entry record
	$result = $mysqli -> query("INSERT INTO `tblentry` (`entryID`,`accountID`,`competitionID`,`entryDate`,`entryFilepath`,`entryTextbox`) VALUES ('".$entryID."','".$_SESSION["accountDetails"]["accountID"]."','".$_SESSION["competitionID"]."',now(),'','".mysql_real_escape_string($_POST["text"])."')"); //Add new entry to entries table using given values
	header ('Location: view_event.php?event='.$_SESSION["competitionID"]) //Redirect to relevant event page
?>