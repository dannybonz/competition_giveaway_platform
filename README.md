# Online Competition and Giveaway Events Platform
This system is an online platform used for the managing and execution of competitions and giveaways (referred to as "events").
Businesses may use this platform to run events, while customers may use this platform to take part in them.

# Setup
This project requires a MySQL database. To connect this project to your own database, edit the data in "database.php". The necessary tables can be generated automatically by running the "create_tables.php" file. A Node.js server is used for sending emails. This requires the Nodemailer module. Every user registered to the platform is given the “Standard” account type by default. Changing an account type to “Admin” is only possible via the backend. To do so, view the “tblaccounts” table in the database and adjust a row’s “accountType” field. Once an admin account exists, you may login to that account and adjust other user’s account types via the frontend.

# Customer Use
Customers can see all currently active events and choose to submit entries following the event organiser’s rules. These entries can also be retracted later if the user changes their mind. The profile page allows customers to update their personal data and see statistics of their activity.

# Business Use
Accounts marked as “Business” type have additional features. These accounts have access to an Event Management page, allowing them to create new events and manage their existing ones. Newly created events require a title, description, rules, start date, deadline, win method, optional entry text requirement and win count. Any event previously created by the signed in user can be viewed from the Event Management page, displaying a list of all submissions and allowing for the ability to select winners and contact participants. When an event’s start date passes, it will start showing up on customer’s feeds and accepting new entries. When the deadline passes, the event will no longer be accessible and cannot receive new entries. It is then up to the business to proceed with awarding the winner.

# Admin Use
Accounts marked as “Admin” type have access to an admin dashboard for moderation. Admins can edit the account type or delete any user from a list. Admins can also manage all events, allowing them to adjust event details or delete them entirely. Deleting an item from the database also deletes certain related ones - for example, deleting a user deletes all of their submitted entries, too. A confirmation dialogue is shown before any action is taken.

# Email Features
Instead of providing businesses with customer email addresses directly, businesses are able to send emails through the platform itself. Confirmation emails are also automatically sent whenever a user submits an entry to an event. 
