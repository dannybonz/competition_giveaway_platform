<?php
	include 'database.php';

	$title = mysql_real_escape_string($_POST["title"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$deadline = $_POST["deadline"];
	$start = $_POST["start"];

	session_start();
	$accountID = $_SESSION['accountDetails']['accountID']; 

	$competitionID = uniqid();
	
	$result = $mysqli -> query("INSERT INTO `tblcompetition` (`competitionID`,`accountID`,`competitionTitle`,`competitionDescription`,`competitionImagePath`,`competitionEndDate`,`competitionWinMethod`,`competitionFileRequirement`,`competitionTextRequirement`,`competitionStartDate`) VALUES ('".$competitionID."','".$accountID."','".$title."','".$description."','None','".$deadline."','Random','None','None','".$start."')");
	$target_file = "event_img/".$competitionID.".png"; //Target location for the image to be saved, using the same filename as the competition ID makes it easy to match

	if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) { //Move image file into target directory, if successful 
		header ('Location:event_management.php');
	}
	else { //If moving the file failed
		$result = $mysqli -> query("DELETE FROM `tblcompetition` WHERE `competitionID` = ".$competitionID); //Remove this failed competition from the table
		header ('Location:error.php');
	}
?>