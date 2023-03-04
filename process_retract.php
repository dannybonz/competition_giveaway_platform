<?php
	//This page is used when a user decides to detract a competition entry. It removes their entry from the entries table.
	include 'database.php';
	session_start(); //Start session
	if (!(isset($_SESSION["accountDetails"]))) { //If not logged in
		header("Location: error.php?e=3"); //Redirect to error page
		exit(); //Cease execution of page
	}	
	$result=$mysqli -> query("SELECT * FROM tblentry WHERE `entryID`='".$_POST["entryID"]."'"); //Find matching entry data
	$count=mysqli_num_rows($result); //Count the number of matches
	if ($count) { 
		$row=mysqli_fetch_assoc($result); //Turn entry data into array 
		if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"])) { //If not logged in as the same user who created the entry
			header("Location: error.php?e=1"); //Redirect to error page
			exit();
		} else {
			$result=$mysqli -> query("DELETE FROM `tblentry` WHERE `entryID`='".$_POST["entryID"]."'"); //Retract entry from competition
			if (isset($_POST["debug"])) {
				echo "Complete";
			} else {
				header ('Location: view_event.php?event='.$_POST["competitionID"]); //Redirect to relevant event view page
			}
		}
	} else { //If no such entry exists
		header("Location: error.php?e=2"); //Redirect to error page
	}
?>