function createAccountPressed() {
	
	let username = $("#inputUsername").val();
	let pass = $("#inputPassword").val();
	let fullName = $("#inputFullName").val();
	let birthday = new Date($('#inputBirthday').val());;
	let email = $("#inputEmail").val();	
	
	$("#createMessage").html('');
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
		var now = new Date();
		if (birthday > now) {
			$("#createMessage").html("Your birthday must be set to a date in the past.");			
		}
		else {
			return true;
		}
	}
	return false;
}

function updateAccountPressed() {
	let fullName = $("#inputFullName").val();
	let email = $("#inputEmail").val();	
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
		return true;
	}
	return false;
}