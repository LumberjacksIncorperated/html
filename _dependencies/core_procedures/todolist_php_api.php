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
	function addTodoListEntryForCurrentUser($todoText, $time) {
		$accountIDOfUser = getAccountIDOfCurrentUser();
		if ($accountIDOfUser) {
			$todoText = sanitiseStringForSQLQuery($todoText);
			modifyDataByMakingSQLQuery("INSERT INTO items (account_id, item_text) VALUES ($accountIDOfUser, \"$todoText\");");
            // modifyDataByMakingSQLQuery("INSERT INTO items (account_id, item_text) VALUES ("2", "my great todo")");
		}
	}

	// INSERT INTO `items` (item_id, account_id, item_text) VALUES (29,1,'Just a demo task, Dan');

	function getTodoListEntries() {
		$r = fetchMultipleRecordsByMakingSQLQuery("SELECT * FROM items");
		return $r;
	}

	// CREATE TAGS
	function createDateTag($dateString){

		return 1;


	}

	// --uuid value: for tagging other users etc?
	// --description might be e.g. "deadline"
	// DROP TABLE IF EXISTS `Tags`;
	// CREATE TABLE Tags (
	// id VARCHAR(36) PRIMARY KEY,
	// tagTypeID INTEGER references TagTypes(id),
	// textValue VARCHAR(100),
	// dateTimeValue DATETIME,
	// numericValue1 FLOAT,
	// numericValue2 FLOAT,
	// numericValue3 FLOAT,
	// uuidValue VARCHAR(36),
	// timeAdded TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	// timeModified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	// addedBy VARCHAR(36) references Accounts(account_id),
	// description VARCHAR(100)
	// );

	// ADD TAGS TO ITEMS

	function addTagForItem($itemID, $tagID){
		$tagID = sanitiseStringForSQLQuery($todoText);
		modifyDataByMakingSQLQuery("INSERT INTO ItemTags (itemID, tagID) VALUES ($itemID, \"$tagID\");");
	}

	function addTagForItemWithOffset($itemID, $tagID, $beginOffset, $endOffset){
		$tagID = sanitiseStringForSQLQuery($todoText);
		modifyDataByMakingSQLQuery("INSERT INTO ItemTags (itemID, tagID, beginOffset, endOffset)
																VALUES ($itemID, \"$tagID\", $beginOffset, $endOffset);");
	}




	// function getTodoListEntrysForCurrentUserWithTodoTextTimePlacePeopleAndTopic($todoText, $time, $place, $people, $topic) {
	// 	$accountIDOfUser = getAccountIDOfCurrentUser();
	// 	if ($accountIDOfUser) {
	// 		$todoText = sanitiseStringForSQLQuery($todoText);
	// 		$time = sanitiseStringForSQLQuery($time);
	// 		$place = sanitiseStringForSQLQuery($place);
	// 		$people = sanitiseStringForSQLQuery($people);
	// 		$topic = sanitiseStringForSQLQuery($topic);
	// 		$todoEntriesFilteredByGivenSearchProperties = fetchMultipleRecordsByMakingSQLQuery("SELECT * FROM todo WHERE (account_id = ".$accountIDOfUser.") AND (time LIKE '%".$time."%') AND (place LIKE '%".$place."%') AND (people LIKE '%".$people."%') AND (topic LIKE '%".$topic."%')");
	// 		return $todoEntriesFilteredByGivenSearchProperties;
	// 	}
	// 	return NULL;
	// }


// https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
// USAGE: echo uuidv4(openssl_random_pseudo_bytes(16));
function uuidv4($data) {
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

?>
