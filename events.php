<?php
	//This page displays all active events a user can participate in
	include 'header.php'; //Adds header to top of page
	include 'database.php'; //Connects to database
?>

<html>
	<head>
		<script src="functions.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	</head>
	
	<div class="container-fluid"><div class="main">
	<div class="row">
	<?php
		$items_in_row=0;
		$result=$mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionStartDate` <= CURDATE() AND `competitionEndDate` > CURDATE()"); //Select all events that have started and have not finished (i.e. currently active)
		if (mysqli_num_rows($result)) {
			while($row=$result->fetch_assoc()) {  //Loop through each event
				if ($items_in_row==3) { //If row is full
					echo '</div><div class="row">'; //Start a new row
					$items_in_row=0;
				}
				echo '<div class="col-sm-4"><div class="event-container" onclick="location.href=\'view_event.php?event='.$row["competitionID"].'\'" style="background-image:url(\'event_img/'.$row["competitionID"].'.png\')"><div class="event-text"><p class="event-title">'.$row["competitionTitle"].'</p><p class="event-desc">'.$row["competitionDescription"]."</p></div></div></div>"; //Display event information
				$items_in_row+=1;
		} else {
			echo '<h1 class="logo" style="margin-top:10%;width:50%;margin-left:25%;">Unfortunately, there are no events you can currently participate in.</h1>';
		}
		echo '</div></div>';
	?>
</html>