<!--This page is included as part of other pages to add the header and navigation bar-->
<html lang="en">
	<head>
		<title>Competition Platform</title>
		<link rel="stylesheet" href="style.css"> 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> <!--Required for Bootstrap-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!--Required for Bootstrap-->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> <!--Required for Bootstrap-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!--Required for jQuery-->
		<script src="functions.js"></script>
	</head>
	<img class="loading" src="loading.png">
	<?php 
	session_start();
	if (isset($_SESSION['accountDetails'])) {

		//Options that every user has
		$buttons = array(array(
			"text" => "Home",
			"id" => "index",
		), array(
			"text" => "View Events",
			"id" => "events",
		),array(
			"text" => "My Page",
			"id" => "profile",
		));
		
		//Options for business account
		if (strcmp($_SESSION["accountDetails"]["accountType"],"Business")==0) {
			array_push($buttons,array(
			"text" => "Event Management",
			"id" => "event_management",
			));
		}
		
		//Options for admin account
		if (strcmp($_SESSION["accountDetails"]["accountType"],"Admin")==0) {
			array_push($buttons,array(
			"text" => "Manage Users",
			"id" => "manage_users",
			));
			array_push($buttons,array(
			"text" => "Manage Events",
			"id" => "manage_events",
			));
		}

		$button_text="";
		foreach ($buttons as $button) { //Loop through all buttons
			$button_text .= '<a href="'.$button["id"].'.php" id="'.$button["id"].'" class="menuButton" style="display:inline;margin-left:10px;margin-bottom:7px;">'.$button["text"].'</a>'; //Add button to page
		}
		echo '<div class="top-bar container-fluid"><div class="navigation-bar col-sm-6">'.$button_text.'</div><div class="login-bar col-sm-6"><p style="display:inline;">Logged in as <b>'.$_SESSION["accountDetails"]["accountName"].'</b></p><a class="menuButton" href="process_signout.php" style="display:inline;margin-left:10px;margin-bottom:7px;">Sign Out</a></div></div>'; //Add Signed In information and logout button
	};?>
	
	<div class="header-container">
		<div class="navbar-header">
			<a href="index.php">
				<img href="index.php" style="height:auto;width:20%" src="logo.png">
			</a>
		</div>
	</div>
</html>
