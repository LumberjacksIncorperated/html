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
// INTERNAL FUNCTIONS
//----------------------------------------

	// Check for any "preferred" name associated with a current tag name
	// Return either the preferred name, or the input name if nothing preferred was found
	function _checkForAssociatedNames($tagName, $accountIDOfUser) {
		$associatedName = fetchSingleRecordByMakingSQLQuery("SELECT outputName from AssociatedNames WHERE 
															userID = $accountIDOfUser 
															AND inputName LIKE \"$tagName\";");
		if ($associatedName['outputName']){
			return $associatedName['outputName'];
		}
		else {
			return $tagName;
		}
	}


//----------------------------------------
// FUNCTIONS
//----------------------------------------

	function getItemIdByItemNumber($itemNumber){
		$itemID = fetchSingleRecordByMakingSQLQuery("SELECT item_id from items where itemNumber = $itemNumber");
		return $itemID['item_id'];
	}


	function addTodoListEntryForCurrentUser($todoText, $time) {

		$accountIDOfUser = getAccountIDOfCurrentUser();
		if ($accountIDOfUser) {
			$todoText = sanitiseStringForSQLQuery($todoText);
			$itemID = uuidv4(openssl_random_pseudo_bytes(16));

			modifyDataByMakingSQLQuery("INSERT INTO items (item_id, account_id, item_text) VALUES (\"$itemID\", $accountIDOfUser, \"$todoText\");");


			tagItem($itemID, $todoText, $accountIDOfUser);

		}
	}

	function tagItem($itemID, $todoText, $accountIDOfUser){

			echo(" tagItem($itemID, $todoText, $accountIDOfUser) ");

			//Tagging functions
			addAllTagsForItem($itemID, $todoText, $accountIDOfUser);		// Google API
			addCustomTagsForItem($itemID, $todoText, $accountIDOfUser);		// Tagnostic secret sauce
			addDateTagForItem($itemID, $time, "manual");					// Manual date
			addAllDateTagsForItem($itemID, $todoText);						//NLP dates and our own dates
			// addNlpDateTagsForItem($itemID, $todoText);					// NLP date api

	}

	// Manual date tagging
	function addDateTagForItem($itemID, $dateString, $taggingMethod){
		$tagID = uuidv4(openssl_random_pseudo_bytes(16));
		$tagTypeID = fetchSingleRecordByMakingSQLQuery("SELECT id from TagTypes WHERE name LIKE \"date\";");
		$tagTypeNumber = $tagTypeID['id'];
		modifyDataByMakingSQLQuery("INSERT INTO Tags (id, tagTypeID, dateTimeValue, description, taggingMethod) 
		                           VALUES (\"$tagID\", $tagTypeNumber, \"$dateString\", \"Due\", \"$taggingMethod\");");
		addTagForItem($itemID, $tagID);
	}

	// NLP date tagging
	function findNlpDateTagsForItem($itemID, $todoText){

		$dateStrings = array();

		// Manually remove things like COMP4920, it thinks these are dates
		$todoText = preg_replace('/[A-Za-z]{4}[0-9]{4}/', '', $todoText);

		$dates = getDateTags($todoText); //Actual function which calls the NLP API
		$mydates = json_decode($dates, true);
		
		for ($i=0; $i < count($mydates[0]); $i++) {
			$dateString = $mydates[0][$i]['date'];

			// Remove timezone things
			$dateString = str_replace("T"," ",$dateString);
			$dateString = str_replace("Z"," ",$dateString);

			array_push($dateStrings, $dateString);
		}
		return $dateStrings;

	}

	// Get array of dates generated by NLP based on text
	// Returns an array of date strings that can be added to DB
	function getNlpDatesForItem($todoText){

		if (preg_match('/^[A-Za-z]{4}[0-9]{4}$/', $todoText)){
			//This is someone searching for a subject, we don't want to know about this
			return NULL;
		}

		$dates = getDateTags($todoText); //Actual function which calls the NLP API
		$mydates = json_decode($dates, true);

		$returnArray = array();
		for ($i=0; $i < count($mydates[0]); $i++) {
			$dateString = $mydates[0][$i]['date'];
			// Remove timezone things
			$dateString = str_replace("T"," ",$dateString);
			$dateString = str_replace("Z","",$dateString);
			array_push($returnArray, $dateString);
		}
		return $returnArray;
	}

	// Google NLP tagging
	function addAllTagsForItem($itemID, $todoText, $accountIDOfUser){

		$tags = getTagsForText($todoText);
		$mytags = json_decode($tags, true);
		$uniqueTagNames = array();

		for ($i=0; $i < count($mytags['entities']); $i++) { 

			$tagName = $mytags['entities'][$i]['name'];
			$tagType = strtolower($mytags['entities'][$i]['type']);

			// Remove escape characters in tag
			$tagName = str_replace("\\","",$tagName);

			if ($tagType == "ORGANIZATION"){
				$tagType = "location";
			}
			elseif ($tagType == "WORK_OF_ART") {
				$tagType = "other";
			}
			elseif ($tagType == "UNKNOWN") {
				$tagType = "other";
			}
			elseif ($tagType == "CONSUMER_GOOD") {
				$tagType = "other";
			}
			elseif(preg_match('/[Pp]riority/', $tagName)){
				// We leave this for our "specialty" function
				continue;
			}

			$tagName = _checkForAssociatedNames($tagName, $accountIDOfUser);

			// Make sure that tags added are unique
			$uniqueTagNames[$tagName] += 1;
			if ($uniqueTagNames[$tagName] == 1){
				$tagID = uuidv4(openssl_random_pseudo_bytes(16));
				addTag($tagName, $tagType, $tagID, "auto");
				addTagForItem($itemID, $tagID);
			}
		}
	}

	// Just get array of Google tags
	function getAllTagsForItem($itemText){

		$tags = getTagsForText($todoText);	//Actual NLP function
		$mytags = json_decode($tags, true);
		$uniqueTagNames = array();
		$returnArray = array();

		for ($i=0; $i < count($mytags['entities']); $i++) { 

			$tagName = $mytags['entities'][$i]['name'];
			$tagType = strtolower($mytags['entities'][$i]['type']);

			// Remove escape characters in tag
			$tagName = str_replace("\\","",$tagName);

			if ($tagType == "ORGANIZATION"){
				$tagType = "location";
			}
			elseif ($tagType == "WORK_OF_ART") {
				$tagType = "other";
			}
			elseif ($tagType == "UNKNOWN") {
				$tagType = "other";
			}
			elseif ($tagType == "CONSUMER_GOOD") {
				$tagType = "other";
			}
			elseif(preg_match('/[Pp]riority/', $tagName)){
				// We leave this for our "specialty" function
				continue;
			}

			$tagName = _checkForAssociatedNames($tagName, $accountIDOfUser);

			// Make sure that tags added are unique
			$uniqueTagNames[$tagName] += 1;
			if ($uniqueTagNames[$tagName] == 1){
				;
			}
		}
	}













	// Tagnostic secret sauce tagging
	function addCustomTagsForItem($itemID, $todoText, $accountIDOfUser) {

		// Add checkbox
		$tagID = uuidv4(openssl_random_pseudo_bytes(16));
		addTag("Done?", "checkbox", $tagID, "auto");
		addTagForItem($itemID, $tagID);

		// Add subjects
		addSubjectsForItem($itemID, $todoText);

		// Add priority
		addPriorityForItem($itemID, $todoText);

	}

	function addAllDateTagsForItem($itemID, $todoText){

		$nlpDates = findNlpDateTagsForItem($itemID, $todoText);
		$customDates = findCustomDateTagsForItem($itemID, $todoText);
		$allDates = array_merge($nlpDates, $customDates);

		// Hackily make sure that we don't double up on dates found
		foreach ($allDates as $dateString) {
			$pattern = '/'.$dateString.'(.)*/';

			if (count(preg_grep($pattern, $allDates)) > 1){
				unset($dateString);
			} else {
				$tagID = uuidv4(openssl_random_pseudo_bytes(16));
				addDateTagForItem($itemID, $dateString, "auto");
			}
		}
	}

	//Add subject tag with simple regex
	function findCustomDateTagsForItem($itemID, $todoText) {

		$mymatches = array();
		$datesToReturn = array();

		$patterns = array(
			'(0|1|2|3)?[0-9] (Jan(uary)?|Feb(ruary)?|Mar(ch)?|Apr(il)?|May|Jun(e)?|Jul(y)?|Aug(ust)?|Sep(tember)?|Oct(ober)?|Nov(ember)?|Dec(ember)?)',
			'(next|last|this)?\s+(Mon(day)?|Tues(day)?|Wednes(day)?|Thurs(day)?|Fri(day)?([^A-Za-z]|$))',
			'\d{1,2}\-\d{1,2}\-\d{4}',
		);

		$patterns_flattened = implode('|', $patterns);

		preg_match_all('/'. $patterns_flattened .'/i', $todoText, $mymatches, PREG_PATTERN_ORDER);

		// Matches[0] holds all the full pattern matches
		foreach ($mymatches[0] as $match) {
			$match = date('Y-m-d', strtotime($match));
			array_push($datesToReturn, $match);
		}
		return $datesToReturn;
	}

	//Add subject tag with simple regex
	function addSubjectsForItem($itemID, $todoText) {

		// Match Australian style subjects like COMP1531 and US style like CS229
		$matches = array();
		preg_match('/[A-Za-z]{2,4}[0-9]{2,4}/', $todoText, $matches);

		foreach ($matches as $match) {
			$tagID = uuidv4(openssl_random_pseudo_bytes(16));
			addTag($match, "other", $tagID, "auto");
			addTagForItem($itemID, $tagID);
		}
	}


	//Add "priority" tag with simple regex
	function addPriorityForItem($itemID, $todoText) {

		$matches = array();
		preg_match('/([a-zA-Z]+) priority/', $todoText, $matches);

		if ($matches[0]){
			$tagID = uuidv4(openssl_random_pseudo_bytes(16));
			addTag($matches[0], "priority", $tagID, "auto");
			addTagForItem($itemID, $tagID);
		}

		if (preg_match('/[Ii]mportant/', $todoText, $matches)) {
			$tagID = uuidv4(openssl_random_pseudo_bytes(16));
			addTag("high priority", "priority", $tagID, "auto");
			addTagForItem($itemID, $tagID);
		}
	}

	// Mark task as completed or not completed
	function toggleTaskCompletion($tagID) {

		$completionStatus = fetchSingleRecordByMakingSQLQuery("SELECT textValue from Tags WHERE id LIKE \"$tagID\";");
		$completionStatus = $completionStatus['textValue'];

		if ($completionStatus === "Done?"){
		    modifyDataByMakingSQLQuery("UPDATE Tags
							SET textValue = \"Done!\"
							WHERE id LIKE \"$tagID\";");
		} 
		if ($completionStatus === "Done!"){
		    modifyDataByMakingSQLQuery("UPDATE Tags
							SET textValue = \"Done?\"
							WHERE id LIKE \"$tagID\";");
		} 
	}


	//get all tags for item (we're only returning the tag name and tag type)
	function getTagsForItem($itemID) {
		$r = fetchMultipleRecordsByMakingSQLQuery("select 
												   COALESCE(Tags.textValue, Tags.dateTimeValue) as textValue, 
												   TagTypes.name as tagType, Tags.id 
												   from ItemTags 
												   JOIN Tags ON Tags.id LIKE ItemTags.tagID 
												   JOIN TagTypes
												   ON TagTypes.id = Tags.tagTypeID
												   where ItemTags.itemID LIKE \"$itemID\";");
		return $r;
	}



	// ADD TAGS
	function addTag($tagName, $tagType, $tagID, $taggingMethod){
		$tagName = sanitiseStringForSQLQuery($tagName);
		$tagTypeID = fetchSingleRecordByMakingSQLQuery("SELECT id from TagTypes WHERE name LIKE \"$tagType\";");
		$tagTypeNumber = $tagTypeID['id'];
		modifyDataByMakingSQLQuery("INSERT INTO Tags (id, tagTypeID, textValue, taggingMethod) 
									VALUES (\"$tagID\", $tagTypeNumber, \"$tagName\", \"$taggingMethod\");");
	}

	function addTagForItem($itemID, $tagID){
		modifyDataByMakingSQLQuery("INSERT INTO ItemTags (itemID, tagID) VALUES (\"$itemID\", \"$tagID\");");
	}

	function addTagForItemWithOffset($itemID, $tagID, $beginOffset, $endOffset){
		modifyDataByMakingSQLQuery("INSERT INTO ItemTags (itemID, tagID, beginOffset, endOffset)
																VALUES ($itemID, \"$tagID\", $beginOffset, $endOffset);");
	}


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
