<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// Take a client request containing a message, and add that message to a local message storage 
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018) and Dan
//--------------------------------------------------------------------------------------------------------------

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/../php_environment_php_api.php';
include_once dirname(__FILE__).'/../database_php_api.php';
include_once dirname(__FILE__).'/secured_session_php_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 
	function addTodoListEntryForCurrentUserWithTodoTextTimePlacePeopleAndTopic($todoText, $time, $place, $people, $topic) {
		$accountIDOfUser = getAccountIDOfCurrentUser();
		if ($accountIDOfUser) {	    
			$todoText = sanitiseStringForSQLQuery($todoText);
			$time = sanitiseStringForSQLQuery($time);
			$place = sanitiseStringForSQLQuery($place);
			$people = sanitiseStringForSQLQuery($people);
			$topic = sanitiseStringForSQLQuery($topic);
			modifyDataByMakingSQLQuery("INSERT INTO todo (account_id, todo_text, time, place, people, topic) VALUES (".$accountIDOfUser.", '".$todoText."', '".$time."', '".$place."', '".$people."', '".$topic."')");
		}
	}



	function getTodoListEntrysForCurrentUserWithTodoTextTimePlacePeopleAndTopic($todoText, $time, $place, $people, $topic) {
		$accountIDOfUser = getAccountIDOfCurrentUser();
		if ($accountIDOfUser) {	    
			$todoText = sanitiseStringForSQLQuery($todoText);
			$time = sanitiseStringForSQLQuery($time);
			$place = sanitiseStringForSQLQuery($place);
			$people = sanitiseStringForSQLQuery($people);
			$topic = sanitiseStringForSQLQuery($topic);
			$todoEntriesFilteredByGivenSearchProperties = fetchMultipleRecordsByMakingSQLQuery("SELECT * FROM todo WHERE (account_id = ".$accountIDOfUser.") AND (time LIKE '%".$time."%') AND (place LIKE '%".$place."%') AND (people LIKE '%".$people."%') AND (topic LIKE '%".$topic."%')");
			return $todoEntriesFilteredByGivenSearchProperties;
		}
		return NULL;
	}

?>

