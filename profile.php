<?php
	//This page allows the user to view and update their personal information
	include 'header.php'; //Adds header to top of page and starts session
	include 'database.php'; //Include database

	if (!(isset($_SESSION["accountDetails"]))) { //If not logged in
		header("Location: error.php?e=3");
		exit();
	}	
?>

<html>
	<head>
		<script src="functions.js"></script>
	</head>

	<div class="main">
		<div class="row" style="margin-top:15px;margin-bottom:30px">
			<h1 class="logo"><?php echo $_SESSION["accountDetails"]["accountUsername"]?>'s Page</h1>
		</div>

		<div class="row">
			<div class="col-sm-3 col-sm-offset-3">
				<div class="purple_boxed max_width">
					<p class="purple_boxed_header">Update Information</p>		
					<form method="post" action="process_update.php" onsubmit="return updateAccountPressed()">
						<?php 
						
							if (isset($_GET["e"])) { //If the error attribute is set, display the corresponding error message
								if ($_GET["e"]=="0") {
									$message="Invalid information received. Ensure your information is valid and try again.";
								}
								elseif ($_GET["e"]=="1") {
									$message="That email address is already in use. Please try again with a different email address.";									
								}
								else {
									$message="An unknown error ocurred. Please try again.";
								}
								echo '<p class="errorMessage" id="returnedMessage">'.$message.'</p>';	
							}						
							
							echo '<p class="form-label">Personal Email Address</p>
							<input class="max_width" name="email" required type="email" id="inputEmail" value="'.$_SESSION["accountDetails"]["accountEmail"].'" placeholder="Enter an email address"><br>
							<p class="form-label">Full Name</p>
							<input class="max_width" name="fullname" required type="text" value="'.$_SESSION["accountDetails"]["accountName"].'" id="inputFullName" placeholder="Enter your full name">
							<p class="form-label">Gender</p>
							<select class="max_width" name="gender" id="gender">';
							
							$genderDescriptions = array("male" => "Male","female" => "Female","other" => "Other / Prefer Not To Say"); //List of possible genders
							
							foreach ($genderDescriptions as $key => $description) { //Loop through each gender 
								if (strcmp($_SESSION["accountDetails"]["accountGender"],$key)==0) { //If this is the currently selected option,
									echo '<option selected value="'.$key.'">'.$description.'</option>'; //Mark as selected
								}
								else {
									echo '<option value="'.$key.'">'.$description.'</option>';								
								}
							}
							
							echo '</select><br>
							<p class="errorMessage" id="createMessage"></p>
							<input type="submit" value="Save Changes" class="button">';
						
						?>
					</form>
				</div>
			</div>
			
			<?php 
				$winning_text="<div class='row'>";
				$entries_text="";
				$events_won=0;
				$bs_column=9;
				$total_entries=0;
				$result=$mysqli -> query("SELECT * FROM tblentry WHERE `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'"); //Select all entries this user has made
				while($row = $result->fetch_assoc()) {  //Loop through each event
					$total_entries+=1;
					$winner_result=$mysqli -> query("SELECT * FROM tblwinner WHERE `competitionID` ='".$row["competitionID"]."' AND `entryID` ='".$row["entryID"]."'"); //Check if the entry was a winner
					$comp_result=$mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$row["competitionID"]."'"); //Get event info
					$comp_info=mysqli_fetch_assoc($comp_result);
					if (mysqli_num_rows($winner_result)) { //If the user won this event
						$events_won+=1;	 
						$bs_column+=1;
						if ($bs_column>3) {
							$bs_column=0;
							$winning_text.'</div><div class="row">';
						}
						$winning_text=$winning_text.'<div class="col-sm-4"><div class="event-container" onclick="location.href=\'view_event.php?event='.$comp_info["competitionID"].'\'" style="background-image:url(\'event_img/'.$comp_info["competitionID"].'.png\')"><div class="event-text"><p class="event-title">'.$comp_info["competitionTitle"].'</p><p class="event-desc">'.$comp_info["competitionDescription"]."</p></div></div></div>"; //Display competition information
					}
					$entries_text=$entries_text.$comp_info["competitionTitle"]." (".$comp_info["competitionEndDate"].")<br>"; //Either way, add the entry to the entries list
				}

				if ($bs_column>0) { //Close div if left open
					$winning_text.'</div>';
				}
				
				if ($entries_text=="") { //If no entries have been listed
					$entries_text="You haven't taken part in any events yet.";					
				}
				
				echo '<div class="col-sm-3">		
				<div class="purple_boxed max_width" style="height:400px;overflow-y:auto;">
					<p class="purple_boxed_header">Entry Stats</p>
					<p>Entries Submitted: '.$total_entries.'<br>Events Won: '.$events_won.'</p><br>
					<p class="purple_boxed_header">Events Entered</p>
					<p>'.$entries_text.'</p>
					</div>
				</div></div>';
				
				if ($winning_text!="<div class='row'>") { //If content has been added to the winning text variable
					echo '<div class="events_won"><h1 class="logo" style="margin-top:30px;">Events Won</h1>';
					echo $winning_text;
					echo '</div>';
				}
			?>			
		</div>
	</div>
</html>