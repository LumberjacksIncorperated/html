<?php

//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To retrieve a single item by ID
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//
//
// Inputs:
// 				itemId - Id front end uses for item....blah
//				session_key - the secure session token
//
// Outputs:
// 				JSON formatted item entry
//
//--------------------------------------------------------------------------------------------------------------

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/items_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 
	if (!ensureThisIsASecuredSession()) {
		echo 'Bad session';
	}

	$itemIdToGet = getIdTextFieldContentsFromCurrentClientRequest();
	displaySingleItemById($itemIdToGet);

?>