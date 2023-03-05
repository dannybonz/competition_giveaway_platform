<?php
	//This page is used when a business account manually chooses upon a winner. It takes the given input and records it in the winners table.
	include 'functions.php'; 

	//Users must be logged in as an admin or business account in order to manage an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1"); //If not logged in as a business or admin account, redirect to error page
		exit(); //Cease execution of this page
	} else {
		include 'database.php'; //If we are logged in, then load the database
		$result=$mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID`='".$_GET["event"]."'"); //Get event data
		$count=mysqli_num_rows($result); //Count the number of matches
		if ($count) { //If a match is found (i.e. there is a competition with the given ID
			$row=mysqli_fetch_assoc($result); //Turn event data into array
			if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"] or $_SESSION["accountDetails"]["accountType"]=="Admin")) { //If not logged in as the same user who created the event, or an admin account
				header("Location: error.php?e=1"); //Redirect to error page
				exit();
			} else {
				$winnerID = uniqid();
				$result = $mysqli -> query("INSERT INTO `tblwinner` (`winnerID`,`competitionID`,`entryID`) VALUES ('".$winnerID."','".$_GET["event"]."','".$_GET["entry"]."')"); //Record new winner
				if (isset($_POST["debug"])) {
					echo "Complete";
				} else {
					header ('Location: manage_event.php?event='.$_GET["event"]); //Redirect to relevant event management page					
				}
			}
		} else {
			header("Location: error.php?e=2"); //If no match is found, redirect to error page
		}
	}
?>