<?php

//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To delete a tag (delete association between tag and item)
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//
//
// Inputs:
// 				
//				tagID - ID of the tag
//				itemID - ID of the item that tag is associated with
//				session_key - secure session key
// Outputs:
// 				Nothing
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
include_once dirname(__FILE__).'/_dependencies/core_procedures/tags_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 
	// Security
	checkSecuredSessionOtherwiseDie();

	$tagId = getTagIDTextFieldContentsFromCurrentClientRequest();
	$itemId = getIdTextFieldContentsFromCurrentClientRequest();

	deleteTag($tagId, $itemId);

?>