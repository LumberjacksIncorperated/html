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

	
    $outerArray = array();
    // $innerArray = array("task" => "hi Dan", "created_at" => "21/09/2020");
    // array_push($outerArray, $innerArray);
    // echo (json_encode($outerArray));

    if ($todoListEntries) {
         foreach ($todoListEntries as $t) {
             $todoEntry = "".$t['item_text'];
             $entryTime = "".$t['time_posted'];

             $tagArray = array();
             $tagOne = array("textValue" => "John", "tagType" => "person");
             $tagTwo = array("textValue" => "UNSW", "tagType" => "location");
             array_push($tagOne, $tagArray);
             array_push($tagTwo, $tagArray);

             $innerArray = array("task" => $todoEntry, "created_at" => $entryTime, "tags" => $tagArray);
             
             // array_push($tagArray, $innerArray);

             array_push($outerArray, $innerArray);
         }
         $reversedOuterArray = array_reverse($outerArray);
         echo (json_encode($reversedOuterArray));


        //  {
        //     "textValue": "John",
        //     "tagType": "person"
        // },
        // {
        //     "textValue": "UNSW",
        //     "tagType": "location"
        // }

        //$outerArray = array();
        //$innerArray = array("task" => $msg, "created_at" => "21/09/2020");
        //array_push($outerArray, $innerArray);
        //echo (json_encode($outerArray));
    } else {
        $outerArray = array();
        $innerArray = array("task" => $msg, "created_at" => "21/09/2020");
        array_push($outerArray, $innerArray);
        echo (json_encode($outerArray));
    }

?>
