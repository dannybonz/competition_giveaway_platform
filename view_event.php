<?php
	include 'header.php'; //Adds header to top of page
	include 'database.php'; //Connect to database
	
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'");
	$count = mysqli_num_rows($result); //Count the number of matches

	$_SESSION["competitionID"]=$_GET["event"];
	
	if($count<1) { //If there isn't a match
		header ("location:error.php");
	}

	$row = mysqli_fetch_assoc($result); //Turn competition data into array 

	if (strtotime($row["competitionStartDate"]) > time()) { //If competition hasn't started yet
		header("Location: error.php?e=1");
		exit();
	}

	echo '<html><div class="container-fluid main">
			<h1 class="logo">'.$row["competitionTitle"].'</h1>
			<div class="event-page-desc" style="background-image:url(\'event_img/'.$row["competitionID"].'.png\')">
				<div class="event-page-gradient">
					<div class="row">
						<div class="col-sm-6">
							<h1 class="logo">Event Description</h1>
							<p class="event-page-paragraph">'.$row["competitionDescription"].'</p>
						</div>
						<div class="col-sm-6">
							<h1 class="logo">Event Rules</h1>
							<p class="event-page-paragraph">'.$row["competitionDescription"].'</p>
						</div>
					</div>
				</div>
			</div>';
?>

	<div style="text-align:center;">
		<div class="purple-boxed">
			<?php
				if (strtotime($row["competitionEndDate"]) <= time()) {
					echo '<p class="event-page-paragraph">This event\'s deadline has passed.</p>
					<p>Entries can no longer be submitted to this event.</p>';
				} else if (isset($_SESSION["accountDetails"])) {
					$result = $mysqli -> query("SELECT * FROM tblentry WHERE `competitionID` ='".$_GET["event"]."' AND `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'");
					$count = mysqli_num_rows($result); //Count the number of matches
					
					if ($count) {
						$entry_row = mysqli_fetch_assoc($result); //Turn entry data into array 
						echo '<form method="post" action="process_retract.php">
						<p class="event-page-paragraph">Your entry to this event has been submitted.</p>
						<p>You may retract your current entry if you wish.</p>
						<input type="submit" id="submit-entry" style="margin-top:15px" value="Retract Entry" class="button">
						<input type="hidden" value="'.$row["competitionID"].'" name="competitionID">
						<input type="hidden" value="'.$entry_row["entryID"].'" name="entryID">
						</form';
					} else {
						echo '<form method="post" action="process_entry.php">
						<p class="event-page-paragraph">Submit your entry using the form below.</p>
						<p>The event organiser will be provided with your name and email address.</p>';
						if ($row["competitionTextRequirement"]!=0) {
						echo '<textarea id="text" name="text" maxlength="'.$row["competitionTextRequirement"].'" placeholder="Write additional text to be included with your entry here..." required class="textarea_input"></textarea><br>';
						}
						echo '<input type="submit" id="submit-entry" style="margin-top:15px" value="Submit Entry" class="button">
						</form>';
					}
				} else {
					echo '<p class="event-page-paragraph">A user account is required to take part in this event.</p>
					<p>Sign in or register a new user account before submitting an entry.</p>';
				}
			?>
		</div>
	</div>
	</div>
</html>