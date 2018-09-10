<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To register
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//--------------------------------------------------------------------------------------------------------------

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
	$registrationResult = attemptToRegisterNewAccountWithUsernameAndPassword($username, $password);
	if($registrationResult) {
		echo "Registration succeded";
	} else {
		echo "Registration failed";
	}
	//$loginSessionKey = getSessionKeyForNewSessionWithUsernameAndPassword($username, $password);
	//if ($loginSessionKey) {
	//	echo $loginSessionKey;
	//} else {
	//	echo 'Failed to login, incorrect username and password';
	//}

?>
