<?php
	//This page is used to allow admin accounts to manage other accounts
	include 'header.php'; //Adds header to top of page
	//Users must be logged in as an admin account
	if ((!isset($_SESSION["accountDetails"])) or (!($_SESSION["accountDetails"]["accountType"]=="Admin"))) {
		header ("Location: error.php?e=1");
		exit();
	}
	include 'database.php';
?>

<html>
	<div class="container-fluid main">
		<div class="table_container">
			<table>
				<tr>
					<th><div class='table_entry'>Account ID</div></th>
					<th><div class='table_entry'>Username</div></th>
					<th><div class='table_entry'>Full Name</div></th>
					<th><div class='table_entry'>Gender</div></th>
					<th><div class='table_entry'>Birthday</div></th>
					<th><div class='table_entry'>Email Address</div></th>
					<th><div class='table_entry'>Account Type</div></th>
					<th><div class='table_entry'>Options</div></th>
				</tr>
				<?php 
					$result=$mysqli -> query("SELECT * FROM tblaccount"); //Select all users
					while($row=$result->fetch_assoc()) {  //Loop through each user
						echo "<tr>
								<td><div class='table_entry'>".$row["accountID"]."</div></td>
								<td><div class='table_entry'>".$row["accountUsername"]."</div></td>
								<td><div class='table_entry'>".$row["accountName"]."</div></td>
								<td><div class='table_entry'>".$row["accountGender"]."</div></td>
								<td><div class='table_entry'>".$row["accountBirthday"]."</div></td>
								<td><div class='table_entry'>".$row["accountEmail"]."</div></td>
								<td><div class='table_entry'>".$row["accountType"]."</div></td>
								<td><div class='table_entry' style='text-align:center;'><div class='admin_select'>								
								<form method='post' style='height: 25px;' action='process_status_update.php'>
								<input type='hidden' name='user' value='".$row["accountID"]."'>
								<select name='type'>
									<option>Standard</option>
									<option>Business</option>
									<option>Admin</option>
								</select>
								<input type='submit' class='admin_button' value='Set Type'></form></div><br>
								<div class='admin_warning'>
								<button class='admin_button' onclick='deleteUserClicked(\"".$row["accountID"]."\")'>Delete User</button></div></div></td>
						</tr>";
					}
				?>
			</table>;				
		</div>
	</div>
	<div class="confirm-delete">
		<div class="purple-boxed" style="text-align:center">
			<p class="event-page-paragraph">Are you sure you wish to delete this user?</p>
			<div class="row" style="width:300px;margin:auto;">
				<div class="col-sm-6">
					<button class="button" onclick="cancelDelete()" style="margin-left:10px">Cancel</button>
				</div>
				<div class="col-sm-6">
					<form method="post" action="process_user_delete.php">
					<input type="hidden" name="user" value="0" id="user">
					<input type="submit" id="delete" class="button" style="margin-left:10px" value="Delete User">
					</form>
				</div>
			</div>
		</div>
	</div>
</html>