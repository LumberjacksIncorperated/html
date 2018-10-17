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


//---------------------------------------- 
// INTERNAL FUNCTIONS
//---------------------------------------- 
    function _createTaskWithoutTags($itemListEntry) {
        $todoEntry = "".$itemListEntry['item_text'];
        $entryTime = "".$itemListEntry['time_posted'];
        $itemID = "".$itemListEntry['item_id'];
        $itemNumber = "".$itemListEntry['itemNumber'];
        $task = array("task" => $todoEntry, "created_at" => $entryTime, "item_id" => $itemNumber, "tag_list" => array());
        return $task;
    }

    function _createTaskFromItemListEntry($itemListEntry) {
        $itemID = "".$itemListEntry['item_id'];
        $task = _createTaskWithoutTags($itemListEntry);
        $itemTags = getTagsForItem($itemID);

        foreach ($itemTags as $itag) {
            array_push($task['tag_list'], array("textValue" => $itag['textValue'], "tagType" => $itag['tagType'], "tagID" => $itag['id']));
        }
        return $task;
    }

    function _displayTaskArrayAsJson($tasksArray) {
        $reversedOuterArray = array_reverse($tasksArray);
        echo (json_encode($reversedOuterArray));
    }

    function _displayDefaultForNoTasks() {
        // nothing here yet...
    }

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

    /// EITHER GET FULL ITEM LIST, OR FILTERED BY QUERY PARAMS ///

    ///////////// QUERY PARAMETERS ////////////////
    $query = getQueryFieldContentsFromCurrentClientRequest();
    if ($query){

        echo("********** $query 6663 ");

        // Somehow the browser interprets "+" as a space, much like %20
        $queryArray = explode(" ", $query);

        foreach ($queryArray as $q) {
            echo("&&&&&& $q &&&&&&&");
            # code...
        }

        $itemListEntriesArray = getItemsByTags($queryArray, $user_id);
    }
    ///////////// NO QUERY PARAMETERS ////////////////
    else {
        $itemListEntriesArray = getAllItemsForUser($user_id);
    }

    $tasksArray = array();

    $size = count($itemListEntriesArray);
    // echo("the size of itemListEntriesArray is $size");


    if ($itemListEntriesArray) {
         foreach ($itemListEntriesArray as $itemListEntry) {
             $task = _createTaskFromItemListEntry($itemListEntry);
             array_push($tasksArray, $task);
         }
         _displayTaskArrayAsJson($tasksArray);

    } else {
        _displayDefaultForNoTasks();
    }
?>
