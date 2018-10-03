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
include_once dirname(__FILE__).'/../nlp_functions.php';

//----------------------------------------
// SCRIPT
//----------------------------------------
	function addTodoListEntryForCurrentUser($todoText, $time) {
		$accountIDOfUser = getAccountIDOfCurrentUser();
		if ($accountIDOfUser) {
			$todoText = sanitiseStringForSQLQuery($todoText);
			$itemID = uuidv4(openssl_random_pseudo_bytes(16));

			modifyDataByMakingSQLQuery("INSERT INTO items (item_id, account_id, item_text) VALUES (\"$itemID\", $accountIDOfUser, \"$todoText\");");

			addAllTagsForItem($itemID, $todoText);
            
		}
	}


	function addAllTagsForItem($itemID, $todoText){

		$tags = getTagsForText($todoText);
		$mytags = json_decode($tags, true);

		for ($i=0; $i < count($mytags['entities']); $i++) { 

			if ($mytags['entities'][$i]['type'] == "ORGANIZATION"){
				$tagTypex = "location";
			}
			elseif ($mytags['entities'][$i]['type'] == "WORK_OF_ART") {
				$tagTypex = "other";
			}
			elseif ($mytags['entities'][$i]['type'] == "UNKNOWN") {
				$tagTypex = "other";
			}
			elseif ($mytags['entities'][$i]['type'] == "CONSUMER_GOOD") {
				$tagTypex = "other";
			}
			elseif($mytags['entities'][$i]['name'] == "priority"){
				// We leave this for our "specialty" function
				continue;
			}
			else {
				$tagTypex = strtolower($mytags['entities'][$i]['type']);
			}

			$tagID = uuidv4(openssl_random_pseudo_bytes(16));
			addTag($mytags['entities'][$i]['name'], $tagTypex, $tagID);
			addTagForItem($itemID, $tagID);
			
		}

			$tagID = uuidv4(openssl_random_pseudo_bytes(16));
			addTag("Done?", "checkbox", $tagID);
			addTagForItem($itemID, $tagID);

			addPriorityForItem($itemID, $todoText);
	}

	function addPriorityForItem($itemID, $todoText) {

		$matches = array();
		preg_match('/([a-zA-Z]+) priority/', $todoText, $matches);

		if ($matches[0]){
			$tagID = uuidv4(openssl_random_pseudo_bytes(16));
			addTag($matches[0], "other", $tagID);
			addTagForItem($itemID, $tagID);
		}
	}


	//We're doing this by item number, which is effectively a second ID
	function deleteItemWithId($num) {
			modifyDataByMakingSQLQuery("DELETE FROM items WHERE itemNumber LIKE \"$num\";");
	}

	function markTaskAsCompleted($tagID) {
	    modifyDataByMakingSQLQuery("UPDATE Tags
									SET textValue = \"Done!\"
									WHERE id LIKE \"$tagID\";");
	}

	
	function getTodoListEntries() {
		$r = fetchMultipleRecordsByMakingSQLQuery("SELECT * FROM items");
		return $r;
	}

	//get all tags for item (we're only returning the tag name and tag type)
	function getTagsForItem($itemID) {
		$r = fetchMultipleRecordsByMakingSQLQuery("select Tags.textValue, TagTypes.name as tagType, Tags.id 
												   from ItemTags 
												   JOIN Tags ON Tags.id LIKE ItemTags.tagID 
												   JOIN TagTypes
												   ON TagTypes.id = Tags.tagTypeID
												   where ItemTags.itemID LIKE \"$itemID\";");
		return $r;
	}

	// CREATE TAGS
	function createDateTag($dateString){

		return 1;

	}


	// ADD TAGS
	function addTag($tagName, $tagType, $tagID){
		$tagName = sanitiseStringForSQLQuery($tagName);
		$tagTypeID = fetchSingleRecordByMakingSQLQuery("SELECT id from TagTypes WHERE name LIKE \"$tagType\";");
		$tagTypeNumber = $tagTypeID['id'];
		modifyDataByMakingSQLQuery("INSERT INTO Tags (id, tagTypeID, textValue) 
									VALUES (\"$tagID\", $tagTypeNumber, \"$tagName\");");
	}

	function addTagForItem($itemID, $tagID){
		modifyDataByMakingSQLQuery("INSERT INTO ItemTags (itemID, tagID) VALUES (\"$itemID\", \"$tagID\");");
	}

	function addTagForItemWithOffset($itemID, $tagID, $beginOffset, $endOffset){
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
