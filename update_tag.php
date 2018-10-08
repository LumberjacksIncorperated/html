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
	if (!ensureThisIsASecuredSession()) {
		echo 'Bad session';
	}

	$tagId = getTagIDTextFieldContentsFromCurrentClientRequest();
	$tagText = getTagTextValueFieldContentsFromCurrentClientRequest();
	echo("the tag is $tagId and the text is $tagText");

	updateTagText($tagText, $tagId);

?>