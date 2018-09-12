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

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 
	$username = getUsernameFieldContentsFromCurrentClientRequest();
	$password = getPasswordFieldContentsFromCurrentClientRequest();
	$loginSessionKey = getSessionKeyForNewSessionWithUsernameAndPassword($username, $password);
	if ($loginSessionKey) {
		echo $loginSessionKey;
	} else {
		echo 'Failed to login, incorrect username and password';
	}

?>
