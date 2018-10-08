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
include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 	
	if (!ensureThisIsASecuredSession()) {
		echo 'Bad session';
	}


    $todoListEntriesArray = getTodoListEntries();

	//this is the "outer array"
    $tasksArray = array();

    if ($todoListEntriesArray) {
         foreach ($todoListEntriesArray as $todoListEntry) {
             $todoEntry = "".$todoListEntry['item_text'];
             $entryTime = "".$todoListEntry['time_posted'];
             $itemID = "".$todoListEntry['item_id'];
             $itemNumber = "".$todoListEntry['itemNumber'];


             $task = array("task" => $todoEntry, "created_at" => $entryTime, "item_id" => $itemNumber, "tag_list" => array());

             // Get list of tags for a particular item
             $itemTags = getTagsForItem($itemID);


             // Push tags to array
             foreach ($itemTags as $itag) {
                array_push($task['tag_list'], array("textValue" => $itag['textValue'], 
                                                    "tagType" => $itag['tagType'],
                                                    "tagID" => $itag['id']));
             }


             array_push($tasksArray, $task);
         }

         $reversedOuterArray = array_reverse($tasksArray);

         echo (json_encode($reversedOuterArray));

         //$task = array("task" => "idk", "created_at" => "5pm", "tag_list" => array());

    } else {
        // fix default for if there is none
        //$outerArray = array();
        //$innerArray = array("task" => $arrayOfTodoListEntries, "created_at" => "21/09/2020");
        ///array_push($outerArray, $innerArray);
        //echo (json_encode($outerArray));
    }

?>
