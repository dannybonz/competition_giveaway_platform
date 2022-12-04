<?php
	include 'database.php';

	$title = mysql_real_escape_string($_POST["title"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$deadline = $_POST["deadline"];
	$start = $_POST["start"];
	$event = $_POST["event"];

	session_start();
	$accountID = $_SESSION['accountDetails']['accountID']; 


	if ($event=="0") {
		$competitionID = uniqid();
		$result = $mysqli -> query("INSERT INTO `tblcompetition` (`competitionID`,`accountID`,`competitionTitle`,`competitionDescription`,`competitionImagePath`,`competitionEndDate`,`competitionWinMethod`,`competitionFileRequirement`,`competitionTextRequirement`,`competitionStartDate`) VALUES ('".$competitionID."','".$accountID."','".$title."','".$description."','None','".$deadline."','Random','None','None','".$start."')");		
	} else {
		$competitionID = $event;
		$result = $mysqli -> query("UPDATE `tblcompetition` SET `competitionTitle` = '".$title."', `competitionDescription` = '".$description."', `competitionEndDate` = '".$deadline."', `competitionStartDate` = '".$start."' WHERE `competitionID` = '".$event."'");			
	}

	if($_FILES["image"]["error"] == 0) {
		$target_file = "event_img/".$competitionID.".png"; //Target location for the image to be saved, using the same filename as the competition ID makes it easy to match
		if(!(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))) { //If the file was not moved succesfully
			header ('Location: error.php'); //Redirect to error page
		};
	}
	header ('Location: manage_event.php?event='.$competitionID); //Redirect to relevant event management page
	echo 'hey there##';
?>