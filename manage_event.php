<?php
	include 'header.php'; //Adds header to top of page and starts session
	include 'functions.php';

	//Users must be logged in as an admin or business account in order to manage an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Business" or $_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	}

	include 'database.php'; //Connect to database
	
	$result=$mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'");
	$count=mysqli_num_rows($result); //Count the number of matches

	$_SESSION["competitionID"]=$_GET["event"];
	
	if ($count<1) { //If there isn't a match
		header ("location:error.php?e=2"); //Redirect to error page
		exit();
	}

	$row=mysqli_fetch_assoc($result); //Turn event data into array 

	if (!($row["accountID"]==$_SESSION["accountDetails"]["accountID"] or $_SESSION["accountDetails"]["accountType"]=="Admin")) { //If not logged in as the same user who created the event, or an admin account
		header("Location: error.php?e=1"); //Redirect to error page
		exit();
	}

	$win_methods=array(
		"random" => "Random Selection",
		"choose" => "Custom Choice"
	);
	
	$max_wins=$row["competitionWinners"];
	$win_method=$row["competitionWinMethod"];

	echo '<html><body><div class="container-fluid main">
			<div style="text-align:center;margin:30px;">
			<a id="edit_event" href="edit_event.php?event='.$row["competitionID"].'" class="button">Edit Event Details</a>
			<button class="button" onclick="deleteClicked()" style="margin-left:10px">Delete Event</button>
			</div>
			<h1 class="logo">'.$row["competitionTitle"].'</h1>
			<p class="event-page-paragraph">Win Method: '.$win_methods[$row["competitionWinMethod"]].' ('.$max_wins.' will win)<br>Start Date: '.$row["competitionStartDate"].' End Date: '.$row["competitionEndDate"].'<br>';

	$now=new DateTime();
	$start=$row["competitionStartDate"];
	$end=$row["competitionEndDate"];
	$start_difference=$now->diff(new DateTime($start));
	$end_difference=$now->diff(new DateTime($end));

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

	$result=$mysqli -> query("SELECT * FROM tblentry WHERE `competitionID` ='".$_GET["event"]."'");
	$count=mysqli_num_rows($result); //Count the number of matches
		
	echo '<br><b>'.$count.'</b> entries submitted.</p>';

	$winners_result=$mysqli -> query("SELECT * FROM tblwinner WHERE `competitionID` ='".$_GET["event"]."'");
	$winner_count=mysqli_num_rows($winners_result);
	$winner_ids=array();

	if (strtotime($row["competitionEndDate"]) <= time()) {

		if ($winner_count<$max_wins and $row["competitionWinMethod"]=="random") {
			$winner=generateWinners($row["competitionID"],$mysqli);
			$winners_result=$mysqli -> query("SELECT * FROM tblwinner WHERE `competitionID` ='".$_GET["event"]."'"); //Get freshly generated winners
			$winner_count=mysqli_num_rows($winners_result);
			$winner_ids=array();
		} else if ($winner_count==0) {
			$winner=false;
		} else {
			$winner=true;
		}

		if ($winner==false) {
			echo '<h1 class="logo">Winning Entries</h1><p class="event-page-paragraph">A winner has not yet been decided for this competition.</p>';
		} else {
			echo '<h1 class="logo">Winning Entries</h1>';
			while($winners_row=$winners_result->fetch_assoc()) {  //Loop through each winning entry
				$entry_result=$mysqli -> query("SELECT * FROM tblentry WHERE `entryID` ='".$winners_row["entryID"]."'");
				$winner=mysqli_fetch_assoc($entry_result);
				$account_result=$mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$winner["accountID"]."'");
				$account_row=mysqli_fetch_assoc($account_result); //Turn winning user data into array 
				echo '<div class="submitted_entry">'.$account_row["accountName"].' ('.$account_row["accountUsername"].') - '.$winner["entryDate"];
				echo '<a href="reset_winner.php?event='.$_GET["event"].'&winner='.$winners_row["winnerID"].'" class="set_winner">Unset Winner</a>';
				if ($winner["entryTextbox"]!="") {
					echo '<div class="submitted_expanded">'.$winner["entryTextbox"].'</div>';	
				}
				echo '<form method="post" action="process_winner_email.php"><input type="hidden" value="'.$winners_row["entryID"].'" name="entryID"><input type="hidden" value="'.$_GET["event"].'" name="competitionID"><div class="submitted_expanded"><textarea required class="textarea_input" style="color:black;" placeholder="Enter email contents" name="emailContents"></textarea>';
				if (isset($_GET["m"])) {
					echo '<p class="errorMessage" id="returnedMessage">Your message has been queued succesfully. It will be sent shortly.</p>';
				}
				echo '<input type="submit" class="button" style="margin-top:10px;" value="Send Email"></div></form></div>';
				array_push($winner_ids,$winner["entryID"]);
			}
		}
	}

	if ($count) {
		echo '<h1 class="logo">Entries Submitted</h1><p class="event-page-paragraph">';
		while($row=$result->fetch_assoc()) {  //Loop through each entry
			if (!(in_array($row["entryID"], $winner_ids))) {
				$account_result=$mysqli -> query("SELECT * FROM tblaccount WHERE `accountID` ='".$row["accountID"]."'");
				$account_row=mysqli_fetch_assoc($account_result); 
				echo '<div class="submitted_entry">'.$account_row["accountName"].' ('.$account_row["accountUsername"].') - '.$row["entryDate"];
				if ($winner_count<$max_wins and $win_method=="choose") {
					echo '<a href="set_winner.php?event='.$_GET["event"].'&entry='.$row["entryID"].'" class="set_winner">Set as Winner</a>';
				}
				if ($row["entryTextbox"]!="") {
					echo '<div class="submitted_expanded">'.$row["entryTextbox"].'</div></div>';	
				} else {
					echo '</div>';
				}
			}
		}
		echo '</p>';
	}
?>
	</div>
	<div class="confirm-delete">
		<div class="purple_boxed" style="text-align:center">
			<p class="event-page-paragraph">Are you sure you wish to delete this event?</p>
			<button class="button" onclick="cancelDelete()" style="margin-left:10px">Cancel</button>
			<?php
				echo '<a href="process_event_delete.php?event='.$_GET["event"].'" id="delete" class="button" style="margin-left:10px">Delete Event</a>';
			?>
		</div>
	</div>
</body>
</html>