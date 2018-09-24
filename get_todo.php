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

    // Leave this bit out for now
	// $todoText = getTodoTextFieldContentsFromCurrentClientRequest();
	//$time = timeTextFieldContentsFromCurrentClientRequest();
	//$place = placeTextFieldContentsFromCurrentClientRequest();
	//$people = peopleTextFieldContentsFromCurrentClientRequest();
	//$topic = topicTextFieldContentsFromCurrentClientRequest();
	
    // $todoListEntries = getTodoListEntrysForCurrentUserWithTodoTextTimePlacePeopleAndTopic($todoText, $time, $place, $people, $topic);

    $msg = "";
    $todoListEntries = getTodoListEntries();
    $msg .= json_encode($todoListEntries);

	
    $tasksArray = array();
    // $innerArray = array("task" => "hi Dan", "created_at" => "21/09/2020");
    // array_push($outerArray, $innerArray);
    // echo (json_encode($outerArray));

    // $task = array("task" => $todoEntry, "created_at" => $entryTime, 
    //    "tag_list" => array(array("textValue" => "John", "tagType" => "person"), 
    //                    array("textValue" => "UNSW", "tagType" => "location")));

    if ($todoListEntries) {
         foreach ($todoListEntries as $t) {
             $todoEntry = "".$t['item_text'];
             $entryTime = "".$t['time_posted'];

             $task = array("task" => $todoEntry, "created_at" => $entryTime, "tag_list" => array());

             array_push($task['tag_list'], array("textValue" => "John", "tagType" => "person"));
             array_push($task['tag_list'], array("textValue" => "UNSW", "tagType" => "location"));

             array_push($tasksArray, $task);
         }
         
         $reversedOuterArray = array_reverse($tasksArray);

         print(json_encode($reversedOuterArray));
         echo (json_encode($reversedOuterArray));

         //$task = array("task" => "idk", "created_at" => "5pm", "tag_list" => array());

    } else {
        $outerArray = array();
        $innerArray = array("task" => $msg, "created_at" => "21/09/2020");
        array_push($outerArray, $innerArray);
        echo (json_encode($outerArray));
    }

?>
