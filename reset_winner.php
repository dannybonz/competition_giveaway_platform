<?php
	session_start();
	if (!isset($_POST["debug"])) { //Necessary for unit test to work
		include 'functions.php';
	}

	//Users must be logged in as an admin or business account in order to manage an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	} else {
		include 'database.php';
		$result=$mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."' AND `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'"); //Get event data
		$row=mysqli_fetch_assoc($result); //Turn event data into array
		if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"] or $_SESSION["accountDetails"]["accountType"]=="Admin")) { //If not logged in as the same user who created the event, or an admin account
			header("Location: error.php?e=1"); //Redirect to error page
			exit();
		} else {
			$result = $mysqli -> query("DELETE FROM `tblwinner` WHERE `winnerID` = '".$_GET["winner"]."' AND `competitionID` ='".$_GET["event"]."'"); //Delete the record of this entry winning
			if (isset($_POST["debug"])) {
				echo "Complete";
			} else {
				header ('Location: manage_event.php?event='.$_GET["event"]); //Redirect to relevant event management page			
			}
		}
	}
?>