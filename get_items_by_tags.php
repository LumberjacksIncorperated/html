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
include_once dirname(__FILE__).'/_dependencies/core_procedures/get_items_by_tag_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 

	
	// Security
	checkSecuredSessionOtherwiseDie();

	// Turn query string into array
	$query = getQueryFieldContentsFromCurrentClientRequest();
	$queryArray = explode("+", $query);

	// Get user id
    $user_id = getAccountIDOfCurrentUser();
    if (! $user_id){
        echo(" Just testing with Bob account ");
        $user_id = 2;
    }

	getItemsByTags($queryArray, $user_id);

	echo 'Successfully sent the query \"'.$query.'\"';
?>