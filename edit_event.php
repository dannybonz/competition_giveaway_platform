<?php //This page is used for both editing old events and creating brand new events

	include 'header.php'; //Adds header to top of page and starts session

	//Users must be logged in as an admin or business account in order to create an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	}
?>

<html>
	<head>
		<script src="home.js"></script>
	</head>
	<div class="main">
		<div class="row" style="margin-top:15px;">
			<div class="purple-bg" style="width:fit-content;margin: 0 auto;margin-bottom:20px;">
				<?php 
					if (isset($_GET["event"])) {
						include 'database.php';
						$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'");
						$count = mysqli_num_rows($result); //Count the number of matches
						if ($count) {
							$row = mysqli_fetch_assoc($result); //Turn event data into array 
							if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"] or $_SESSION["accountDetails"]["accountType"]=="Admin")) { //If not logged in as the same user who created the event, or an admin account
								header("Location: error.php?e=1"); //Redirect to error page
								exit();
							} else {
								$title = $row["competitionTitle"];
								$desc = $row["competitionDescription"];
								$rules = $row["competitionRules"];
								$start = $row["competitionStartDate"];
								$end = $row["competitionEndDate"];
								$image = "Optionally upload a new image to replace the event's current image.";
								$button = "Save Changes";
								$image_req = ""; //Image not required if editing instead of creating
								$event = $_GET["event"];
								$text = $row["competitionTextRequirement"];
								$win = $row["competitionWinMethod"];
								$winners = $row["competitionWinners"];
							}
						} else {
							header("Location: error.php?e=2");
							exit();
						}
					} else {
						$title = "";
						$desc = "";
						$rules = "";
						$text = "0";
						$start = "";
						$end = "";
						$image = "Upload an image to be used for your event.";
						$button = "Create Event";
						$image_req = "required";
						$event = "0";
						$win = "random";
						$winners = "";
					}
					
					echo '<form method="post" action="process_event_create.php" enctype="multipart/form-data">
						<p class="form-label">Enter your event\'s title.</p>
						<input type="text" required style="width:100%" name="title" value="'.$title.'" placeholder="Event title"><br>
						<p class="form-label">Enter your event\'s description.</p>
						<textarea class="textarea_input" maxlength="500" required name="description" placeholder="Event description">'.$desc.'</textarea><br>
						<p class="form-label">Enter your event\'s rules.</p>
						<textarea class="textarea_input" maxlength="500" required name="rules" placeholder="Event rules">'.$rules.'</textarea><br>
						<p class="form-label">Enter the competition\'s start date.</p>
						<input required name="start" type="date" value="'.$start.'"><br>
						<p class="form-label">Enter the deadline for entry.</p>
						<input required name="deadline" type="date" value="'.$end.'"><br>
						<p class="form-label">Choose whether additional text entry is required.</p>
						<select name="text" id="text">';
							
					$textDescriptions = array("0" => "None","50" => "Up to 50 characters","100" => "Up to 100 characters","300" => "Up to 300 characters"); //Possible options for text limit
					
					foreach ($textDescriptions as $key => $description) {
						if (strcmp($text,$key)==0) { //If this is the previously selected option
							echo '<option selected value="'.$key.'">'.$description.'</option>'; //Mark it as selected in the HTML
						}
						else {
							echo '<option value="'.$key.'">'.$description.'</option>';								
						}
					}
					
					echo '</select><br>
						<p class="form-label">Choose how the winner will be decided.</p>
						<select name="win" id="win">';

					$winDescriptions = array("random" => "Random Selection","choose" => "Custom Choice"); //Possible options for how the winner is decided
					
					foreach ($winDescriptions as $key => $description) {
						if (strcmp($win,$key)==0) { //If this is the previously selected option
							echo '<option selected value="'.$key.'">'.$description.'</option>'; //Mark it as selected in the HTML
						}
						else {
							echo '<option value="'.$key.'">'.$description.'</option>';								
						}
					}

					echo '</select><br>
						<p class="form-label">Enter how many winners your event will have.</p>
						<input type="number" name="winners" id="winners" placeholder="Number of winners" value="'.$winners.'">
						<p class="form-label">'.$image.'</p>
						<input '.$image_req.' type="file" name="image" id="image" class="button" style="margin-top:15px"><br>
						<input type="submit" id="save_event" value="'.$button.'" class="button">
						<input type="hidden" name="event" value="'.$event.'">';
				?>
				</form>
			</div>
		</div>
	</div>
</html>