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

	function deleteItemWithId($id) {
			modifyDataByMakingSQLQuery("DELETE FROM items WHERE item_id = $id;");
	}
	

	function getTodoListEntries() {
		$r = fetchMultipleRecordsByMakingSQLQuery("SELECT * FROM items");
		return $r;
	}

	//get all tags for item (we're only returning the tag name and tag type)
	function getTagsForItem($itemID) {
		$r = fetchMultipleRecordsByMakingSQLQuery("select Tags.textValue, TagTypes.name as tagType 
												   from ItemTags 
												   JOIN Tags ON Tags.id = ItemTags.tagID 
												   JOIN TagTypes
												   ON TagTypes.id = Tags.tagTypeID
												   where ItemTags.itemID = $itemID;");
		return $r;
	}

	// CREATE TAGS
	function createDateTag($dateString){

		return 1;

	}


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


// GENERATE UUID
// https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
// USAGE: echo uuidv4(openssl_random_pseudo_bytes(16));
function uuidv4($data) {
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

?>
