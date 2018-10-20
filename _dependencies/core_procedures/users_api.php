<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018) and Dan
//--------------------------------------------------------------------------------------------------------------

//----------------------------------------
// INCLUDES
//----------------------------------------
include_once dirname(__FILE__).'/../php_environment_php_api.php';
include_once dirname(__FILE__).'/../database_php_api.php';
include_once dirname(__FILE__).'/secured_session_php_api.php';
include_once dirname(__FILE__).'/todolist_php_api.php';

//----------------------------------------
// FUNCTIONS
//----------------------------------------

function createNewUserAccount($username,$password,$email,$firstName,$lastName) {

	// Security
	$username = sanitiseStringForSQLQuery($username);
	$email = sanitiseStringForSQLQuery($email);
	$firstName = sanitiseStringForSQLQuery($firstName);
	$lastName = sanitiseStringForSQLQuery($lastName);

	if (strlen($password) < 8){
		return "Error: Password must be at least 8 characters"; 
	}

	$password = sha1($password);

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return "Error: Invalid email format"; 
	}
	$checkUserName = fetchSingleRecordByMakingSQLQuery("SELECT * from Accounts WHERE username LIKE \"$username\";");
	if ($checkUserName) {
		return "Error: User name is already taken";
	}
	$checkEmail = fetchSingleRecordByMakingSQLQuery("SELECT * from Accounts WHERE email LIKE \"$email\";");
	if ($checkEmail) {
		return "Error: Email address is already associated with an account";
	}

	modifyDataByMakingSQLQuery("INSERT INTO Accounts (username,password_sha1,firstName,lastName,email) values (\"$username\", \"$password\",\"$firstName\",\"$lastName\",\"$email\");");

	$checkUserName = fetchSingleRecordByMakingSQLQuery("SELECT * from Accounts WHERE username LIKE \"$username\";");
	if ($checkUserName) {
		return "success";
	}
	return "Error: Failed to create new account. Please try again or contact support";
}


?>