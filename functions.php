<?php
	function getWinner($competitionID, $mysqli) {
		$result = $mysqli -> query("SELECT * FROM tblcompetition WHERE `competitionID` ='".$_GET["event"]."'");
		$row = mysqli_fetch_assoc($result); //Turn event data into array
		if ($row["competitionWinningEntry"]=="") {
			$entries_array = array();
			if ($row["competitionWinMethod"]=="random") {
				$entry_result = $mysqli -> query("SELECT * FROM tblentry WHERE `competitionID` ='".$_GET["event"]."'");
				while($entry_row = $entry_result->fetch_assoc()) {  //Loop through each entry
					$entries_array[]=$entry_row;
				}
				
				if (count($entries_array)>0) {
					$winning_entry=$entries_array[array_rand($entries_array)];
					$result = $mysqli -> query("UPDATE tblcompetition SET competitionWinningEntry = '".$winning_entry["entryID"]."' WHERE `competitionID` = '".$competitionID."'"); //Update recorded user data
					return($winning_entry);
				} else {
					return("no winner");
				}
			} else {
				return("no winner");
			}	
		} else {
			$entry_result = $mysqli -> query("SELECT * FROM tblentry WHERE `entryID` ='".$row["competitionWinningEntry"]."'");
			$winning_entry = mysqli_fetch_assoc($entry_result);
			return($winning_entry);		
		}
	}
?>