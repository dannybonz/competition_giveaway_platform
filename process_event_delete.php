<?php
	include 'database.php'; //Connect to database

	$result = $mysqli -> query("DELETE FROM `tblcompetition` WHERE `competitionID` = '".$_GET["event"]."'"); //Delete competition based on given ID
	$result = $mysqli -> query("DELETE FROM `tblentry` WHERE `competitionID` = '".$_GET["event"]."'"); //Delete any entries for the competition
	
	header ('Location: event_management.php');
?>