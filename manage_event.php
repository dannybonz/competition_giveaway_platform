<?php
	include 'header.php'; //Adds header to top of page
	include 'database.php'; //Connect to database
	
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'");
	$count = mysqli_num_rows($result); //Count the number of matches

	$_SESSION["competitionID"]=$_GET["event"];
	
	if($count<1) { //If there isn't a match
		header ("location:error.php");
	}

	$row = mysqli_fetch_assoc($result); //Turn event data into array 

	echo '<html><body><div class="container-fluid main">
			<div style="text-align:center;margin:30px;">
			<a id="edit_event" href="edit_event.php?event='.$row["competitionID"].'" class="button">Edit Event Details</a>
			<button class="button" onclick="deleteClicked()" style="margin-left:10px">Delete Event</button>
			</div>
			<h1 class="logo">'.$row["competitionTitle"].'</h1>
			<p class="event-page-paragraph">Start Date: '.$row["competitionStartDate"].' End Date: '.$row["competitionEndDate"].'<br>';

	$now = new DateTime();
	$start = $row["competitionStartDate"];
	$end = $row["competitionEndDate"];
	$start_difference = $now->diff(new DateTime($start));
	$end_difference = $now->diff(new DateTime($end));

	if ($row["competitionStartDate"] > $now) {
		echo 'Starts in '.$start_difference->days.' days. ';
	} else {
		
		echo 'Started '.$start_difference->days.' days ago. ';
	}

	if ($row["competitionEndDate"] > $now) {
		echo 'Ends in '.$end_difference->days.' days.';
	} else {
		
		echo 'Ended '.$end_difference->days.' days ago.';
	}

	$result = $mysqli -> query("SELECT * FROM tblentry WHERE `competitionID` ='".$_GET["event"]."'");
	$count = mysqli_num_rows($result); //Count the number of matches
		
	echo '<br><b>'.$count.'</b> entries submitted.</p>';

	if ($count) {
		echo '<h1 class="logo">Entries Submitted</h1><p class="event-page-paragraph">';
		while($row = $result->fetch_assoc()) {  //Loop through each entry
			$account_result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$row["accountID"]."'");
			$account_row = mysqli_fetch_array($account_result); 
			echo $account_row["accountName"].' ('.$account_row["accountUsername"].'#'.$row["accountID"].') - '.$row["entryDate"].'<br>';
		}
		echo '</p>';
	}

?>
	</div>
	<div class="confirm-delete">
		<div class="purple-boxed" style="text-align:center">
			<p class="event-page-paragraph">Are you sure you wish to delete this event?</p>
			<button class="button" onclick="cancelDelete()" style="margin-left:10px">Cancel</button>
			<?php
				echo '<a href="process_event_delete.php?event='.$_GET["event"].'" id="delete" class="button" style="margin-left:10px">Delete Event</a>';
			?>
		</div>
	</div>
</body>
</html>