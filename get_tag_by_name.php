<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To get messages of current user
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018) and Dan
//
//
// PARAMETERS: 'tagText'
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

    function _createTagListArrayFromQueryResults(){
        ;
    }



    function _displayArrayAsJson($array) {
        echo (json_encode($array));
    }

    function _displayDefaultForNoTasks() {
        // nothing here yet...
    }

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 	
	if (!ensureThisIsASecuredSession()) {
		echo 'Bad session';
	}

    ///////////// QUERY PARAMETERS ////////////////
    $tagText = getTagTextValueFieldContentsFromCurrentClientRequest();

    $userId = 2; //TODO

    $tagsArray = getTagsByNameAndUser($tagText, $userId); 

     var_dump($tagsArray);

     echo "---------------------";

    _displayArrayAsJson($tagsArray);






    // $query = getQueryFieldContentsFromCurrentClientRequest();
    // if ($query){

    //     // Get user id
    //     // TODO
    //     $user_id = 2;

    //     // Somehow the browser interprets "+" as a space, much like %20?
    //     $queryArray = explode(" ", $query);

    //     // echo("query = ".$query);
    //     // var_dump($queryArray);

    //     $itemListEntriesArray = getItemsByTags($queryArray, $user_id);
    // }
    // ///////////// NO QUERY PARAMETERS ////////////////
    // else {
    //     $itemListEntriesArray = getTodoListEntries();
    // }

    // $tasksArray = array();

    // $size = count($itemListEntriesArray);
    // // echo("the size of itemListEntriesArray is $size");


    // if ($itemListEntriesArray) {
    //      foreach ($itemListEntriesArray as $itemListEntry) {
    //          $task = _createTaskFromItemListEntry($itemListEntry);
    //          array_push($tasksArray, $task);
    //      }
    //      _displayTaskArrayAsJson($tasksArray);

    // } else {
    //     _displayDefaultForNoTasks();
    // }
?>
