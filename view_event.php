<?php
	include 'header.php'; //Adds header to top of page
	include 'database.php'; //Connect to database
	
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'");
	$count = mysqli_num_rows($result); //Count the number of matches

	$_SESSION["competitionID"]=$_GET["event"];
	
	if($count<1) { //If there isn't a match
		header ("location:error.php");
	}

	$row = mysqli_fetch_assoc($result); //Turn user data into array 

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
			<form method="post" action="process_entry.php">
			<?php
				$result = $mysqli -> query("SELECT * FROM tblentry WHERE `competitionID` ='".$_GET["event"]."' AND `accountID` ='".$_SESSION["accountDetails"]["accountID"]."'");
				$count = mysqli_num_rows($result); //Count the number of matches
				
				if ($count) {
					echo '<p class="event-page-paragraph">You have already submitted an entry to this event.</p>';
				} else {
					echo '<p class="event-page-paragraph">Submit your entry using the form below.</p>
					<input type="submit" id="submit-entry" style="margin-top:15px" value="Join Competition" class="button">';
				}
			?>
			</form>
		</div>
	</div>
	</div>
</html>