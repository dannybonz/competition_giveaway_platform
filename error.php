<?php
	//This page is used to display error messages when something goes wrong.
	include 'header.php'; //Adds header to top of page and connects to database
?>

<html>
	<head>
		<script src="home.js"></script>
	</head>
	<div class="main">
			
		<div class="row" style="margin-top:15px;">
			<h1 class="logo">An error ocurred.</h1>
			<?php
				if (isset($_GET["e"])) { //Use provided error value to display relevant message
					if ($_GET["e"]=="0") {
						$message="The provided image could not be uploaded.";
					}
					elseif ($_GET["e"]=="1") {
						$message="User has insufficient permissions.";
					}
					elseif ($_GET["e"]=="2") {
						$message="This object does not exist.";
					}
					elseif ($_GET["e"]=="3") {
						$message="Not logged in to a user account.";
					}
					elseif ($_GET["e"]=="4") {
						$message="Invalid information provided for form.";
					}
					elseif ($_GET["e"]=="5") {
						$message="Form already submitted.";
					}
					else {
						$message="Unknown error code.";
					}
					echo '<p class="event-page-paragraph">'.$message.'</p>'; //Display contents of message variable
				}
			?>
		</div>
		
	</div>
</html>