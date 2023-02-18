<?php
	//This page is used when an admin account chooses to delete a user account.
	session_start();
	//Users must be logged in as an admin account in order to delete an event
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1"); //If not logged in, redirect to error page
		exit(); //Cease execution of page
	}
	
	include 'database.php';
	$result = $mysqli -> query("DELETE FROM `tblaccount` WHERE `accountID` = '".$_POST["user"]."'"); //Delete the user's account record
	$result = $mysqli -> query("DELETE FROM `tblentry` WHERE `accountID` = '".$_POST["user"]."'"); //Delete the user's entries
	$result = $mysqli -> query("DELETE FROM `tblwinner` WHERE `accountID` = '".$_POST["user"]."'"); //Delete the user's wins
	$result = $mysqli -> query("DELETE FROM `tblcompetition` WHERE `accountID` = '".$_POST["user"]."'"); //Delete the user's competitions
	
	header ('Location: manage_users.php'); //Redirect back to event management page
?>