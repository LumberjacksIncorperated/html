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

    $msg = "yo there ";
    $todoListEntries = getTodoListEntries();
    $msg .= json_encode($todoListEntries);

	
    // $outerArray = array();
    // $innerArray = array("task" => "hi Dan", "created_at" => "21/09/2020");
    // array_push($outerArray, $innerArray);
    // echo (json_encode($outerArray));

    if ($todoListEntries) {
        // foreach ($todoListEntries as $todoListEntry) {
        //     $todoEntry = "".$todoListEntry['item_text'];
        //     $innerArray = array("task" => $todoEntry, "created_at" => "21/09/2018");
        //     array_push($outerArray, $innerArray);
        // }
        // $reversedOuterArray = array_reverse($outerArray);
        // echo (json_encode($reversedOuterArray));

        $outerArray = array();
        $innerArray = array("task" => $msg, "created_at" => "21/09/2020");
        array_push($outerArray, $innerArray);
        echo (json_encode($outerArray));


    } else {
        $outerArray = array();
        $innerArray = array("task" => $msg, "created_at" => "21/09/2020");
        array_push($outerArray, $innerArray);
        echo (json_encode($outerArray));
    }

?>
