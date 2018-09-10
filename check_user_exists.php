<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To check if user exists
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//--------------------------------------------------------------------------------------------------------------

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
#include_once dirname(__FILE__).'/../_dependencies/core_procedures/recieved_message_storage_php_api.php';
#include_once dirname(__FILE__).'/../_dependencies/php_environment_php_api.php';
#include_once dirname(__FILE__).'/../_dependencies/core_procedures/secured_session_php_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 
	
#	if (!ensureThisIsASecuredSession()) {
		echo 'true';
#	} else {
#		$usernameToCheck = getUsernameFieldContentsFromCurrentClientRequest();
#		if (userDoesExistForUsername($usernameToCheck)) {
#			echo 'true';
#		} else {
#			echo 'false';
#		}
#	}

?>
