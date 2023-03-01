<?php
	//This page is used to allow admin accounts to manage events
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
					<th><div class='table_entry'>Event ID</div></th>
					<th><div class='table_entry'>Account ID</div></th>
					<th><div class='table_entry'>Title</div></th>
					<th><div class='table_entry'>Options</div></th>
				</tr>
				<?php 
					$result=$mysqli -> query("SELECT * FROM tblcompetition"); //Select all users
					while($row=$result->fetch_assoc()) {  //Loop through each user
						echo "<tr>
								<td><div class='table_entry'>".$row["competitionID"]."</div></td>
								<td><div class='table_entry'>".$row["accountID"]."</div></td>
								<td><div class='table_entry'>".$row["competitionTitle"]."</div></td>
								<td><div class='table_entry' style='text-align:center;'><div class='admin_select' style='margin-bottom:5px;'>								
								<a href='manage_event.php?event=".$row["competitionID"]."' style='color:black;'><button class='admin_button')'>Edit Event</button></a></div>
								<div class='admin_warning'>
								<button class='admin_button' onclick='deleteEventClicked(\"".$row["competitionID"]."\")'>Delete Event</button></div></div></td>
						</tr>";
					}
				?>
			</table>;				
		</div>
	</div>
	<div class="confirm-delete">
		<div class="purple-boxed" style="text-align:center">
			<p class="event-page-paragraph">Are you sure you wish to delete this event?</p>
			<div class="row" style="width:400px;margin:auto;">
				<button class="button" onclick="cancelDelete()" style="margin-left:10px">Cancel</button>
				<a class="button" id='delete' style="margin-left:10px;">Delete Event</a>
			</div>
		</div>
	</div>
</html>