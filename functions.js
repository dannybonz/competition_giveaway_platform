//This file provides Javascript functions for other pages to use

function createAccountPressed() { //Validates input on the frontend
	//Get user input values
	let username=$("#inputUsername").val();
	let pass=$("#inputPassword").val();
	let fullName=$("#inputFullName").val();
	let birthday=new Date($('#inputBirthday').val());;
	let email=$("#inputEmail").val();	
	$("#createMessage").html(''); //Reset error message
	if (username.length>10 || username.length<1) {
		$("#createMessage").html("Usernames must be between 1 and 10 characters long.");
	}
	else if (!(username.match("^[A-Za-z0-9]+$"))){
		$("#createMessage").html("Usernames cannot include special characters.");
	}
	else if (pass.length<4 || pass.length>30) {
		$("#createMessage").html("Passwords must be between 4 and 30 characters long.");		
	}
	else if (email.length<4 || email.length>30) {
		$("#createMessage").html("Email addresses must be between 4 and 30 characters long.");		
	}
	else if (fullName.length<3 || fullName.length>40) {
		$("#createMessage").html("Your full name must be between 3 and 40 characters long.");
	}
	else if (!(fullName.match("^[A-Za-z0-9 ]+$"))){
		$("#createMessage").html("Your full name cannot include special characters.");
	}
	else {
		var now=new Date();
		if (birthday > now) {
			$("#createMessage").html("Your birthday must be set to a date in the past.");			
		}
		else {
			displayLoad(); //Display loading screen
			return true; //Success!
		}
	}
	return false; //Invalid
}

function deleteClicked() { //Bring up Delete Confirmation screen
	$(".main *:not(.confirm-delete)").fadeOut();
	$(".confirm-delete").fadeIn();
}

function cancelDelete() { //Hide Delete Confirmation screen
	$(".confirm-delete").hide();
	$(".main *:not(.confirm-delete)").fadeIn();
}

function deleteUserClicked(accountID) {
	$("#user").val(accountID);
	deleteClicked();
}

function updateAccountPressed() { //Validates input on the frontend
	//Get user input values
	let fullName=$("#inputFullName").val();
	let email=$("#inputEmail").val();	
	if (email.length<4 || email.length>30) {
		$("#createMessage").html("Email addresses must be between 4 and 30 characters long.");		
	}
	else if (fullName.length<3 || fullName.length>40) {
		$("#createMessage").html("Your full name must be between 3 and 40 characters long.");
	}
	else if (!(fullName.match("^[A-Za-z0-9 ]+$"))){
		$("#createMessage").html("Your full name cannot include special characters.");
	}
	else {
		displayLoad(); //Display loading screen
		return true; //Success!
	}
	return false; //Invalid
}

function displayLoad() { //Used to display loading screen 
	$("body *:not(.loading)").fadeOut(); //Fade out everything that's not the loading spinner
	$(".loading").fadeIn(500) //Fade in the loading spinner
}

$(window).on("load", function() {
	$("body").on("click",".event-container,#events,#edit_event,#submit-entry,#save_event,#event_management,#delete", function(event){ //Add event listener to trigger loading screens after certain actions
		if (!(event.ctrlKey)) { //Not holding control (new tab)
			displayLoad(); //Display loading screen
		};
	});
});