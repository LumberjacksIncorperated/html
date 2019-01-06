<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To get messages of current user
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//--------------------------------------------------------------------------------------------------------------

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/items_api.php';
include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/get_items_by_tag_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/charts_api.php';


//---------------------------------------- 
// INTERNAL FUNCTIONS
//---------------------------------------- 
    

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 	
    
    // Security
    checkSecuredSessionOtherwiseDie();

    // Get user id
    $user_id = getAccountIDOfCurrentUser();
    if (! $user_id){
        echo("Just testing with Bob account");
        $user_id = 2;
    }

    //Get top tags for user
    $topTags = getTopSixTagsForUser($user_id);

    echo (json_encode($topTags));

?>
