<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To login:
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//--------------------------------------------------------------------------------------------------------------

// Header needed for react

header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';


//---------------------------------------- 
// SCRIPT
//---------------------------------------- 
	$username = getUsernameFieldContentsFromCurrentClientRequest();
	$password = getPasswordFieldContentsFromCurrentClientRequest();
	$email = getEmailFieldContentFromCurrentClientRequest();
	$firstName = getFirstNameFieldContentFromCurrentClientRequest();
	$lastName = getLastNameFieldContentFromCurrentClientRequest();

	// $loginSessionKey = getSessionKeyForNewSessionWithUsernameAndPassword($username, $password);
	// if ($loginSessionKey) {
	// 	echo $loginSessionKey;
	// } else {
	// 	echo 'Failed to login, incorrect username and password';
	// }

	echo("$username $password $email $firstName $lastName");

	createNewUserAccount($username,$password,$email,$firstName,$lastName);
?>
