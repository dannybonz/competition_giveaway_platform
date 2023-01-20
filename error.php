<?php
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
				if (isset($_GET["e"])) {
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
					else {
						$message="Unknown error code.";
					}
					echo '<p class="event-page-paragraph">'.$message.'</p>';	
				}
			?>
		</div>
		
	</div>
</html>