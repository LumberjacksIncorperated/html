<?php

//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To edit a tag
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//
//
// Inputs:
// 				tagText - text value of the tag
//				tagID - ID of the tag
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

	// Get user id
    $user_id = getAccountIDOfCurrentUser();
    if (! $user_id){
        echo(" Just testing with Bob account ");
        $user_id = 2;
    }

	$tag_text = getTagTextValueFieldContentsFromCurrentClientRequest();
	$item_number = getIdTextFieldContentsFromCurrentClientRequest();

	$item_id = getItemIdByItemNumber($item_number);
	
	addManualTag($item_id, $tag_text, $user_id);

	echo("******** adding tag $tag_text to item $item_id ************** ");

?>