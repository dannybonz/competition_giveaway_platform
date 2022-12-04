<?php

	include 'database.php';
	session_start(); //Start session
	$result = $mysqli -> query("INSERT INTO `tblentry` (`entryID`,`accountID`,`competitionID`,`entryDate`,`entryFilepath`,`entryTextbox`) VALUES (NULL,'".$_SESSION["accountDetails"]["accountID"]."','".$_SESSION["competitionID"]."',now(),'','')"); //Add new entry to entries table using given values
	header ('Location: view_event.php?event='.$_SESSION["competitionID"])
?>