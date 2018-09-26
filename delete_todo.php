<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// Take a client request containing a tag id, and delete that message 
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
	if (!ensureThisIsASecuredSession()) {
		echo 'You are not allowed to send messages unless you are logged into a secure session';
		die();
	}

	$item_id = getIdTextFieldContentsFromCurrentClientRequest();

	deleteItemWithId($item_id);
	echo 'Successfully deleted item \"'.$item_id;
?>

