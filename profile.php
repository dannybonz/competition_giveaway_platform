<?php
	include 'header.php'; //Adds header to top of page and connects to database
?>

<html>
	<head>
		<script src="home.js"></script>
	</head>

	<div class="main">
		<div class="row" style="margin-top:15px;">
			<h1 class="logo">Welcome to your profile!</h1>
		</div>


		<div class="purple-boxed">
			<form method="post" action="process_update.php" onsubmit="return updateAccountPressed()">
				<?php 
				
					if (isset($_GET["e"])) {
						if ($_GET["e"]=="0") {
							$message="Invalid information received. Ensure your information is valid and try again.";
						}
						elseif ($_GET["e"]=="1") {
							$message="That email address is already in use. Please try again with a different email address.";									
						}
						else {
							$message="An unknown error ocurred. Please try again.";
						}
						echo '<p class="errorMessage" id="returnedMessage">'.$message.'</p>';	
					}						
					
					echo '<p class="form-label">Personal Email Address</p>
					<input name="email" required type="email" id="inputEmail" value="'.$_SESSION["accountDetails"]["accountEmail"].'" placeholder="Enter an email address"><br>
					<p class="form-label">Full Name</p>
					<input name="fullname" required type="text" value="'.$_SESSION["accountDetails"]["accountName"].'" id="inputFullName" placeholder="Enter your full name">
					<p class="form-label">Gender</p>
					<select name="gender" id="gender">';
					
					$genderDescriptions = array("male" => "Male","female" => "Female","other" => "Other / Prefer Not To Say");
					
					foreach ($genderDescriptions as $key => $description) {
						if (strcmp($_SESSION["accountDetails"]["accountGender"],$key)==0) {
							echo '<option selected value="'.$key.'">'.$description.'</option>';
						}
						else {
							echo '<option value="'.$key.'">'.$description.'</option>';								
						}
					}
					
					echo '</select><br>
					<p class="errorMessage" id="createMessage"></p>
					<input type="submit" value="Save Changes" class="button">';
				
				?>
			</form>
		</div>
	</div>
</html>