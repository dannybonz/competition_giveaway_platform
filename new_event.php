<?php
	include 'header.php'; //Adds header to top of page and connects to database
?>

<html>
	<head>
		<script src="home.js"></script>
	</head>
	<div class="main">
		<div class="row" style="margin-top:15px;">
			<div class="purple-bg" style="width:fit-content;margin: 0 auto">
				<form method="post" action="process_event_create.php" enctype="multipart/form-data">
					<?php 
						if (isset($_GET["e2"])) {
							echo '<p class="errorMessage" id="returnedMessage">The provided username and password do not match. Please try again.</p>';	
						};
					?>
					<p class="form-label">Enter your event's title.</p>
					<input type="text" required style="width:100%" name="title" placeholder="Event title"><br>
					<p class="form-label">Enter your event's description and rules.</p>
					<textarea style="width:100%" required name="description" placeholder="Event description"></textarea><br>
					<p class="form-label">Enter the competition's start date.</p>
					<input required name="start" type="date"><br>
					<p class="form-label">Enter the deadline for entry.</p>
					<input required name="deadline" type="date"><br>
					<p class="form-label">Upload an image to be used for your event.</p>
					<input required type="file" name="image" id="image" class="button" style="margin-top:15px"><br>
					<input style="margin-top:20px" type="submit" value="Create Event" class="button">
				</form>
			</div>
		</div>
	</div>
</html>