<?php
	//This page is used to display a list of events the currently logged in user can manage
	include 'header.php'; //Adds header to top of page
	//Users must be logged in as an admin or business account in order to manage events
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	}
	include 'database.php';
?>

<html>
	<head>
		<script src="home.js"></script>
	</head>
	<div class="container-fluid main">
		<div style="text-align:center;margin:30px;">
			<a href="edit_event.php" class="button">Create New Event</a>
		</div>
			<?php 
				
				$subheadings = array(); //We don't know which subheadings we'll need. The ones that we need will be added to this array.
		
				$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `accountID` = '".$_SESSION["accountDetails"]["accountID"]."' AND `competitionStartDate` <= CURDATE() AND `competitionEndDate` > CURDATE()"); //Select all events that have started and have not finished (i.e. currently active)
				$count = mysqli_num_rows($result); //Count the number of matches
				if ($count) { //There is a currently active competition, so we'll include an Active Now subheading
					array_push($subheadings,array(
						"text" => "Active Now",
						"result" => $result
					));
				}

				$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `accountID` = '".$_SESSION["accountDetails"]["accountID"]."' AND `competitionStartDate` > CURDATE()"); //Select all events that have not yet started
				$count = mysqli_num_rows($result); //Count the number of matches
				if ($count) { //There is an upcoming competition, so we'll include an Coming Up subheading
					array_push($subheadings,array(
						"text" => "Coming Up",
						"result" => $result
					));
				}

				$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `accountID` = '".$_SESSION["accountDetails"]["accountID"]."' AND `competitionEndDate` < CURDATE()"); //Select all events that have already finished
				$count = mysqli_num_rows($result); //Count the number of matches
				if ($count) {  //There is a completed competition, so we'll include an Completed Events subheading
					array_push($subheadings,array(
						"text" => "Completed Events",
						"result" => $result
					));
				}
								
				foreach ($subheadings as $subheading) { //Loop through all subheadings
					echo '<h1 class="logo" style="margin-bottom:0px; margin-top: 50px;">'.$subheading["text"].'</h1>
					<div class="row">';
					$items_in_row=0;

					while($row = $subheading["result"]->fetch_assoc()) {  //Loop through each event
						if ($items_in_row==3) { //If the row has exceeded 3 items
							echo '</div><div class="row">'; //Start a new row
							$items_in_row=0;
						}
						echo '<div class="col-sm-4"><div class="event-container" onclick="location.href=\'manage_event.php?event='.$row["competitionID"].'\'" style="background-image:url(\'event_img/'.$row["competitionID"].'.png\')"><div class="event-text"><p class="event-title">'.$row["competitionTitle"].'</p><p class="event-desc">'.$row["competitionDescription"]."</p></div></div></div>"; //Display event information
						$items_in_row+=1;
					}
					echo '</div>';
				}
			?>
	</div>
</html>