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

	$todoText = getTodoTextFieldContentsFromCurrentClientRequest();
	$time = timeTextFieldContentsFromCurrentClientRequest();
	$place = placeTextFieldContentsFromCurrentClientRequest();
	$people = peopleTextFieldContentsFromCurrentClientRequest();
	$topic = topicTextFieldContentsFromCurrentClientRequest();
	$todoListEntries = getTodoListEntrysForCurrentUserWithTodoTextTimePlacePeopleAndTopic($todoText, $time, $place, $people, $topic);
	if ($todoListEntries) {
		$todoListEntriesInAWonkyFormatSeperatedByHashes = "";
		foreach ($todoListEntries as $todoListEntrie) {
			$todoListEntrieInWonkyFormat = "".$todoListEntrie['todo_text'];#.$todoListEntrie['time']."~".$todoListEntrie['place']."~".$todoListEntrie['people']."~".$todoListEntrie['topic'];
			$todoOne = "".$todoListEntrie['todo_text'];#.$todoListEntrie['time']."~".$todoListEntrie['place']."~".$todoListEntrie['people']."~".$todoListEntrie['topic'];
			$todoListEntriesInAWonkyFormatSeperatedByHashes = $todoListEntriesInAWonkyFormatSeperatedByHashes.$todoListEntrieInWonkyFormat;
            #break;
		}
		#echo $todoListEntriesInAWonkyFormatSeperatedByHashes;
        echo $todoOne;

	} else {
		echo 'Bad data';
	}


?>
