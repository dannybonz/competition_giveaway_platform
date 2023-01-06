<?php
	include 'header.php'; //Adds header to top of page and starts session
	include 'functions.php';

	//Users must be logged in as an admin or business account in order to manage an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	}

	include 'database.php'; //Connect to database
	
	$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'");
	$count = mysqli_num_rows($result); //Count the number of matches

	$_SESSION["competitionID"]=$_GET["event"];
	
	if ($count<1) { //If there isn't a match
		header ("location:error.php?e=2");
		exit();
	}

	$row = mysqli_fetch_assoc($result); //Turn event data into array 

	$win_methods = array(
		"random" => "Random Selection",
	);

	echo '<html><body><div class="container-fluid main">
			<div style="text-align:center;margin:30px;">
			<a id="edit_event" href="edit_event.php?event='.$row["competitionID"].'" class="button">Edit Event Details</a>
			<button class="button" onclick="deleteClicked()" style="margin-left:10px">Delete Event</button>
			</div>
			<h1 class="logo">'.$row["competitionTitle"].'</h1>
			<p class="event-page-paragraph">Win Method: '.$win_methods[$row["competitionWinMethod"]].'<br>Start Date: '.$row["competitionStartDate"].' End Date: '.$row["competitionEndDate"].'<br>';

	$now = new DateTime();
	$start = $row["competitionStartDate"];
	$end = $row["competitionEndDate"];
	$start_difference = $now->diff(new DateTime($start));
	$end_difference = $now->diff(new DateTime($end));

	if (strtotime($row["competitionStartDate"]) > time()) {
		echo 'Starts in '.$start_difference->days.' days. ';
	} else {
		
		echo 'Started '.$start_difference->days.' days ago. ';
	}

	if (strtotime($row["competitionEndDate"]) > time()) {
		echo 'Ends in '.$end_difference->days.' days.';
	} else {
		echo 'Ended '.$end_difference->days.' days ago.';
	}

	$result = $mysqli -> query("SELECT * FROM tblentry WHERE `competitionID` ='".$_GET["event"]."'");
	$count = mysqli_num_rows($result); //Count the number of matches
		
	echo '<br><b>'.$count.'</b> entries submitted.</p>';

	if (strtotime($row["competitionEndDate"]) <= time()) {
		if ($row["competitionWinningEntry"]=="") {
			$winner = getWinner($row["competitionID"],$mysqli);
		} else {
			$winner_result = $mysqli -> query("SELECT * FROM tblentry WHERE `entryID` ='".$row["competitionWinningEntry"]."'");
			$winner = mysqli_fetch_assoc($result); //Turn winning entry data into array 
		}
		if ($winner=="no winner") {
			echo '<h1 class="logo">Winning Entry</h1><p class="event-page-paragraph">A winner could not be chosen for this competition due to lack of participation.</p>';
		} else {
			$account_result = $mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$winner["accountID"]."'");
			$account_data = mysqli_fetch_assoc($account_result); //Turn winning user data into array 
			echo '<h1 class="logo">Winning Entry</h1><p class="event-page-paragraph">';
			echo 'Entry ID: '.$winner["entryID"].'<br>
			Submitted by: '.$account_data["accountName"].' ('.$account_data["accountUsername"].'#'.$account_data["accountID"].')<br>
			Email Address: '.$account_data["accountEmail"].'</p>';						
		}
	}

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