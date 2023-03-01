<?php
	//This page is used to create a new competition. It takes the given information and adds a new entry in the competitions table. 
	session_start();
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {	//Users must be logged in as an admin or business account in order to create an event
		header ("Location: error.php?e=1"); //Redirect to error page
		exit(); //Cease execution of page
	} else {
		include 'database.php'; 

		$title = mysql_real_escape_string($_POST["title"]); //Escape string to prevent injection
		$description = mysql_real_escape_string($_POST["description"]);
		$deadline = mysql_real_escape_string($_POST["deadline"]);
		$start = mysql_real_escape_string($_POST["start"]);
		$event = mysql_real_escape_string($_POST["event"]);
		$win = mysql_real_escape_string($_POST["win"]);
		$text = mysql_real_escape_string($_POST["text"]);
		$winners = mysql_real_escape_string($_POST["winners"]);
		$accountID = $_SESSION['accountDetails']['accountID']; 

		if ($event=="0") { //If this is a brand new event
			$competitionID = uniqid(); 
			$result = $mysqli -> query("INSERT INTO `tblcompetition` (`competitionWinners`,`competitionWinMethod`,`competitionID`,`accountID`,`competitionTitle`,`competitionDescription`,`competitionImagePath`,`competitionEndDate`,`competitionFileRequirement`,`competitionTextRequirement`,`competitionStartDate`) VALUES ('".$winners."','".$win."','".$competitionID."','".$accountID."','".$title."','".$description."','None','".$deadline."','None','".$text."','".$start."')"); 
			echo "INSERT INTO `tblcompetition` (`competitionWinners`,`competitionWinMethod`,`competitionID`,`accountID`,`competitionTitle`,`competitionDescription`,`competitionImagePath`,`competitionEndDate`,`competitionFileRequirement`,`competitionTextRequirement`,`competitionStartDate`) VALUES ('".$winners."','".$win."','".$competitionID."','".$accountID."','".$title."','".$description."','None','".$deadline."','None','".$text."','".$start."')";
		} else {
			$competitionID = $event;

			$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$competitionID."'");
			$count = mysqli_num_rows($result); //Count the number of matches
			
			if($count<1) { //If there isn't a match
				header ("location:error.php?e=2"); //Redirect to error page
				exit();
			}

			$row = mysqli_fetch_assoc($result); //Turn competition data into array 
			if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"] or $_SESSION["accountDetails"]["accountType"]=="Admin")) { //If not logged in as the same user who created the event, or an admin account
				header ("location:error.php?e=1"); //Redirect to error page
				exit();
			}

			$result = $mysqli -> query("UPDATE `tblcompetition` SET `competitionWinners` = '".$winners."', `competitionTitle` = '".$title."', `competitionTextRequirement` = '".$text."', `competitionWinMethod` = '".$win."', `competitionDescription` = '".$description."', `competitionEndDate` = '".$deadline."', `competitionStartDate` = '".$start."' WHERE `competitionID` = '".$event."'");			
		}

		if($_FILES["image"]["error"] == 0) {
			$target_file = "event_img/".$competitionID.".png"; //Target location for the image to be saved, using the same filename as the competition ID makes it easy to match
			if(!(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))) { //If the file was not moved succesfully
				header ('Location: error.php?e=0'); //Redirect to error page
				exit();
			};
		}

		header ('Location: manage_event.php?event='.$competitionID); //Redirect to relevant event management page
	}
?>