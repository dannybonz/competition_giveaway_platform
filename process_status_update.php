<?php
	//This page is used when an admin modifies an account's type (i.e. switches it between Standard, Business or Admin).
	session_start(); //Start session

	//Users must be logged in as an admin account
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	}

	include 'database.php';
	$result=$mysqli -> query("UPDATE tblaccount SET accountType='".$_POST["type"]."' WHERE `accountID`='".$_POST["user"]."'"); //Update recorded user data
	header ('Location:manage_users.php'); //Return to user management page
?>