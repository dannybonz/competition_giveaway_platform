<?php
	//Users must be logged in as an admin or business account in order to create an event
	session_start();
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	} else {
		include 'database.php';

		$title = mysql_real_escape_string($_POST["title"]);
		$description = mysql_real_escape_string($_POST["description"]);
		$deadline = $_POST["deadline"];
		$start = $_POST["start"];
		$event = $_POST["event"];
		$win = $_POST["win"];
		$text = $_POST["text"];

		$accountID = $_SESSION['accountDetails']['accountID']; 

		if ($event=="0") {
			$competitionID = uniqid();
			$result = $mysqli -> query("INSERT INTO `tblcompetition` (`competitionWinMethod`,`competitionID`,`accountID`,`competitionTitle`,`competitionDescription`,`competitionImagePath`,`competitionEndDate`,`competitionFileRequirement`,`competitionTextRequirement`,`competitionStartDate`) VALUES ('".$win."','".$competitionID."','".$accountID."','".$title."','".$description."','None','".$deadline."','None','".$text."','".$start."')");		

		} else {
			$competitionID = $event;

			$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$competitionID."'");
			$count = mysqli_num_rows($result); //Count the number of matches
			
			if($count<1) { //If there isn't a match
				header ("location:error.php?e=2");
				exit();
			}

			$row = mysqli_fetch_assoc($result); //Turn competition data into array 
			if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"])) { //Cannot edit competitions you did not create
				header ("location:error.php?e=1");
				exit();
			}

			$result = $mysqli -> query("UPDATE `tblcompetition` SET `competitionTitle` = '".$title."', `competitionTextRequirement` = '".$text."', `competitionWinMethod` = '".$win."', `competitionDescription` = '".$description."', `competitionEndDate` = '".$deadline."', `competitionStartDate` = '".$start."' WHERE `competitionID` = '".$event."'");			
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