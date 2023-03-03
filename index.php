<?php
	include 'header.php'; //Adds header to top of page
?>

<html>
	<head>
		<script src="functions.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!--Allows for social media icons-->
	</head>
	<div class="main container-fluid">
		<div class="row" style="margin-top:15px;text-align:center;">
			<h1 class="logo">Welcome to CompEe!</h1>
			<div class="purple_boxed" style="float:none;margin:0 auto;margin:15px;">
				<p style="margin-top:10px;">CompEe allows businesses and organisations to run competitions and giveaways online.<br><b>Register now to take part in events!</b></p>
			</div>
		</div>

		<div class="row" style="margin-top:15px;">

			<div class="col-sm-4 col-sm-offset-2">
				<div class="purple_boxed max_width" style="float: none; margin: 0 auto;">
					<p class="purple_boxed_header">Registration</p>
					<form method="post" onsubmit="return createAccountPressed()" action="process_register.php">
						<?php 
						
							if (isset($_GET["registered"])) { //If directed to this page with the registered attribute set, display confirmation message
								echo '<p class="errorMessage" id="returnedMessage">Your account has been succesfully registered. You may now log in.</p>';
							}
							else {
								if (isset($_GET["e"])) { //If directed to this page with the error attribute set, display the relevant error message
									if ($_GET["e"]=="0") {
										$message="Invalid signup information received. Ensure your information is valid and try again.";
									}
									elseif ($_GET["e"]=="1") {
										$message="That username is already in use. Please try again with a different username.";
									}
									elseif ($_GET["e"]=="2") {
										$message="That email address is already in use. Please try again with a different email address.";									
									}
									else {
										$message="An unknown error ocurred. Please try again.";
									}
									echo '<p class="errorMessage" id="returnedMessage">'.$message.'</p>';	
								}
								echo '<p class="form-label">Create a username for your account.</p>
								<input required name="username" class="max_width" type="text" id="inputUsername" placeholder="Enter a username"><br>
								<p class="form-label">Choose a secure and memorable password.</p>
								<input required name="password" class="max_width" type="password" id="inputPassword" placeholder="Enter a password"><br>
								<p class="form-label">Enter your personal email address.</p>
								<input required name="email" class="max_width" type="email" id="inputEmail" placeholder="Enter an email address"><br>
								<p class="form-label">Enter your full name.</p>
								<input required name="fullname" class="max_width" type="text" id="inputFullName" placeholder="Enter your full name">
								<p class="form-label">Enter your birthday.</p>
								<input required name="birthday" class="max_width" type="date" id ="inputBirthday">
								<p class="form-label">Select your gender.</p>
								<select name="gender" class="max_width" id="gender">
								  <option value="male">Male</option>
								  <option value="female">Female</option>
								  <option value="other">Other / Prefer Not To Say</option>
								</select><br>
								<p class="errorMessage" id="createMessage"></p>
								<input type="submit" value="Create Account" class="button">';
							}
						
						?>
					</form>
				</div>
			</div>
			
			<div class="col-sm-4">
				<div class="purple_boxed max_width">
					<p class="purple_boxed_header">Login</p>
					<form method="post" action="process_login.php">
						<?php 
							if (isset($_GET["e2"])) { //If login error attribute is set, display error message.
								echo '<p class="errorMessage" id="returnedMessage">The provided username and password do not match. Please try again.</p>';	
							};
						?>
						<p class="form-label">Enter your account's username.</p>					
						<input type="text" class="max_width" required name="username" placeholder="Username"><br>
						<p class="form-label">Enter your account's password.</p>
						<input type="password" class="max_width" required name="password" placeholder="Password"><br>
						<input style="margin-top:20px" type="submit" value="Log In" class="button">
					</form>
				</div>
			</div>
		</div>
	</div>
	<footer><p class="footer_text">Contact Us: <a class='white_link' href='mailto:contact@compee.com'>contact@compee.com</a><a href="#" class="fa fa-facebook white_link"></a><a href="#" class="fa fa-twitter white_link"></a></p></footer>
</html>