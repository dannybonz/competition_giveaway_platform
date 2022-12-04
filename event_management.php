<?php
	include 'header.php'; //Adds header to top of page
	include 'database.php';
?>

<html>
	<head>
		<script src="home.js"></script>
	</head>
	<div class="container-fluid main">
		<div style="text-align:center;margin:30px;">
			<a href="new_event.php" class="button">Create New Event</a>
		</div>
			<?php 
				
				$subheadings = array();
		
				$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionStartDate` < CURDATE() AND `competitionEndDate` > CURDATE()"); //Select all events that have started and have not finished (i.e. currently active)
				$count = mysqli_num_rows($result); //Count the number of matches
				if ($count) {
					array_push($subheadings,array(
						"text" => "Active Now",
						"result" => $result
					));
				}

				$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionStartDate` > CURDATE()"); //Select all events that have not yet started
				$count = mysqli_num_rows($result); //Count the number of matches
				if ($count) {
					array_push($subheadings,array(
						"text" => "Coming Up",
						"result" => $result
					));
				}

				$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionEndDate` < CURDATE()"); //Select all events that have already finished
				$count = mysqli_num_rows($result); //Count the number of matches
				if ($count) {
					array_push($subheadings,array(
						"text" => "Completed Events",
						"result" => $result
					));
				}
								
				foreach ($subheadings as $subheading) {
					echo '<h1 class="logo" style="margin-bottom:0px; margin-top: 50px;">'.$subheading["text"].'</h1>
					<div class="row">';
					$items_in_row=0;

					while($row = $subheading["result"]->fetch_assoc()) {  //Loop through each event
						if ($items_in_row==3) {
							echo '</div><div class="row">';
							$items_in_row=0;
						}
						echo '<div class="col-sm-4"><div class="event-container" onclick="location.href=\'manage_event.php?event='.$row["competitionID"].'\'" style="background-image:url(\'event_img/'.$row["competitionID"].'.png\')"><div class="event-text"><p class="event-title">'.$row["competitionTitle"].'</p><p class="event-desc">'.$row["competitionDescription"]."</p></div></div></div>"; //Display event location and date
						$items_in_row+=1;
					}
					echo '</div>';
				}
			?>
	</div>
</html>