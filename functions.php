<?php
	//This page provides functions to be used by other pages.
	
	//If random winners have not yet been generated, generate them now.
	function generateWinners($competitionID, $mysqli) {
		$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'"); //Find matching competition information
		$count = mysqli_num_rows($result); //Count the number of matches
		$row = mysqli_fetch_assoc($result); //Turn competition data into array 
		
		$result=$mysqli -> query("SELECT * FROM tblwinner WHERE `competitionID` ='".$_GET["event"]."'"); //Get the winners
		$count = mysqli_num_rows($result); //Count the number of matches
		
		if ($count<$row["competitionWinners"] and $row["competitionWinMethod"]=="random") { //If there is no winner decided
			
			while($winner_row=$result->fetch_assoc()) { //Loop through each entry
				$winners_array[]=$winner_row["entryID"];
			}

			$entry_result=$mysqli -> query("SELECT * FROM tblentry WHERE `competitionID` ='".$_GET["event"]."'");
			while($entry_row=$entry_result->fetch_assoc()) { //Loop through each entry
				if (!(in_array($entry_row["entryID"], $winners_array))) {
					$entries_array[]=$entry_row;
				}
			}

			shuffle($entries_array); //Randomise array order
			if (count($entries_array)>0) {
				if (count($entries_array)<$row["competitionWinners"]) { //If there are fewer entries than winners, everyone will be a winner
					foreach ($entries_array as $entry) { //Loop through every entry and mark it as a winner
						$result = $mysqli -> query("INSERT INTO `tblwinner` (`winnerID`,`competitionID`,`entryID`) VALUES ('".uniqid()."','".$entry["competitionID"]."','".$entry["entryID"]."')");
					}
				} else {
					$winners_to_add=$row["competitionWinners"]-$count;
					for ($x = 0; $x < $winners_to_add; $x++) {
						$result = $mysqli -> query("INSERT INTO `tblwinner` (`winnerID`,`competitionID`,`entryID`) VALUES ('".uniqid()."','".$entries_array[$x]["competitionID"]."','".$entries_array[$x]["entryID"]."')"); //Record new winner
					}				
				}
				return(true); //Winners generated succesfully
			}
		}
		return(false); //No winners generated
	}
?>