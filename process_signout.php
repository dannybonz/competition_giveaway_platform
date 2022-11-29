<?php
	session_start(); //If a session hasn't been created yet, create one 
	session_destroy(); //Destroy the current session
	header ('Location: index.php'); //Redirect to login page
?>
