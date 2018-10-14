<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// Take a client request containing a message, and add that message to a local message storage 
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
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 
	
	// Security
	checkSecuredSessionOtherwiseDie();

	$todoText = getTodoTextFieldContentsFromCurrentClientRequest();
	$time = timeTextFieldContentsFromCurrentClientRequest();
	addTodoListEntryForCurrentUser($todoText, $time);
	echo 'Successfully sent the message \"'.$todoText.'\", time: '.$time;
?>

