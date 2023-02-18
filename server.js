const mysql = require('mysql');
const nodemailer = require('nodemailer');

//Configure NodeMailer transporter for use with gmail 
const transporter = nodemailer.createTransport({ 
    service: "Gmail",
	debug: true,
	logger: true,
	port: 587,
    auth: {
        user: "turtleeventsapp@gmail.com",
        pass: "rlerbygbthkaoxot" //Application password
    }
});

//Configure MySQL connection
const con = mysql.createConnection({
	host: "127.0.0.1",
	user: "danny",
	password: "a",
	database: "events_platform"
});

con.connect(function(err) { //Connect to database
	if (err) throw err;

	console.log("Database connected");

	var interval=setInterval(function() { //Set regular interval for scanning
	
		con.query("SELECT * FROM tblentry WHERE `entryEmailed` = '0'", function (err, result, fields) { //Get all entries that haven't had confirmation emails sent
			if (err) throw err;

			result.forEach(function (item, index) { //Loop through all relevant entries
				console.log("Submission confirmation email triggered")
				
				con.query("SELECT * FROM tblcompetition WHERE `competitionID` = '"+item["competitionID"]+"'", function (err, competition_result, fields) { //Get competition info
					if (err) throw err;
					
					//Set email options and contents
					var mailOptions = { 
						from: 'Turtle Events',
						to: 'bonzorio1@gmail.com', //Just use my email for now, change this in future
						subject: competition_result[0]["competitionTitle"]+' Entry Confirmation',
						html: '<div style="background-image:linear-gradient(to bottom right, #F6C2CB, #730973);width:90%;text-align:center;font-family:sans-serif;padding-top:20px;padding-bottom:10px;border-radius:200px;margin-left: 5%;margin-top:50px;margin-bottom:50px"><img src=\'https://cdn.discordapp.com/attachments/344919769813745674/1065806877872619531/logo.png\' style="width:auto;height:100px;margin-bottom:20px"><br><h1 style="text-align:center;color:white;background-color: rgba(128, 0, 128, 0.1);display:inline-block;padding:15px;border-radius:15px;width:50%">Your submission to '+competition_result[0]["competitionTitle"]+' has been received!<br>Thank you for taking part.</h1><p style="color:white;margin-top:50px;font-weight:bold;font-style:italic;">This email was sent automatically to confirm your entry. You have not been added to a mailing list.</p></div>'
					};
					
					//Send email
					transporter.sendMail(mailOptions, function(error, info){
						if (error) {
							console.log(error);
						} else {
						console.log('Email sent: ' + info.response);
						}
					});

					//Update database entry (to prevent multiple emails)
					con.query("UPDATE tblentry SET entryEmailed = '1' WHERE entryID = '"+item["entryID"]+"'", function (err, result) {
						if (err) throw err;
						console.log(result.affectedRows + " rows updated");
					});
				});
			});
		});

		con.query("SELECT * FROM tblemail WHERE `emailEmailed` = '0'", function (err, result, fields) { //Get all event organiser emails that haven't been sent
			if (err) throw err;

			result.forEach(function (item, index) { //Loop through all relevant entries
				console.log("Event organiser email triggered")
				
				con.query("SELECT * FROM tblaccount WHERE `accountID` = '"+item["senderID"]+"'", function (err, sender_result, fields) { //Get sender info
					if (err) throw err;
					
					//Set email options and contents
					var mailOptions = { 
						from: 'Turtle Events',
						to: sender_result["accountEmail"], 
						subject: 'Message from '+sender_result[0]["accountName"],
						html: '<div style="background-image:linear-gradient(to bottom right, #F6C2CB, #730973);width:90%;text-align:center;font-family:sans-serif;padding-top:20px;padding-bottom:10px;border-radius:200px;margin-left: 5%;margin-top:50px;margin-bottom:50px"><img src=\'https://cdn.discordapp.com/attachments/344919769813745674/1065806877872619531/logo.png\' style="width:auto;height:100px;margin-bottom:20px"><br><h1 style="text-align:center;color:white;background-color: rgba(128, 0, 128, 0.1);display:inline-block;padding:15px;border-radius:15px;width:50%">'+sender_result[0]["accountName"]+' sent you the following message.</h1><br><h1 style="text-align:center;color:white;background-color: rgba(128, 0, 128, 0.1);display:inline-block;padding:15px;border-radius:15px;width:50%">'+item["emailContents"]+'</h1><p style="color:white;margin-top:50px;font-weight:bold;font-style:italic;">This email was triggered by the event organiser initiating a message through the Turtle Events platform. Your email address has not been shared with the event organiser.</p></div>'
					};
					
					//Send email
					transporter.sendMail(mailOptions, function(error, info){
						if (error) {
							console.log(error);
						} else {
						console.log('Email sent: ' + info.response);
						}
					});

					//Update database entry (to prevent sending multiple times)
					con.query("UPDATE tblemail SET emailEmailed = '1' WHERE emailID = '"+item["emailID"]+"'", function (err, result) {
						if (err) throw err;
						console.log(result.affectedRows + " rows updated");
					});
				});
			});
		});


	}, 1000);


});





