<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// Retrieve items from database based on tags
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
include_once dirname(__FILE__).'/todolist_php_api.php';


//----------------------------------------
// INTERNAL FUNCTIONS
//----------------------------------------


//----------------------------------------
// FUNCTIONS
//----------------------------------------


// Return a list of items with tags that match the query parameters
function getItemsByTags($queryArray, $accountId){

	// Match date tags and add to query array
	foreach ($queryArray as $queryString) {

		Account for different date types coming from FE
		if (preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $queryString, $matches)){
			$newQueryString = $matches[3].'-'.$matches[2].'-'.$matches[1];
			$queryString = $newQueryString;
		}

		echo "((((((((((( $queryString ))))))))))";

		// $customDates = findCustomDateTagsForItem($itemID, $todoText);
		// $nlpDates = findNlpDateTagsForItem($itemID, $todoText);
		// $datesArray = array_merge($customDates, $nlpDates);

		$datesArray = getNlpDatesForItem($queryString);

		if ($datesArray != NULL){
			$queryString = str_replace(".000","",$datesArray[0]);
			array_push($queryArray, $queryString);
		}
	}

	//Turn query array into a string in the form: 'John', 'Newtown', 'coffee', etc
	$queryArrayString = "";
	foreach ($queryArray as $queryItem) {
		$queryArrayString = $queryArrayString."'".$queryItem."', "; 
	}
	// Remove trailing comma/space 
	$queryArrayString = rtrim($queryArrayString,", ");

	$numberOfQueryItems = count($queryArray);

	// Intermediate views
	modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW user_items as SELECT * from items where account_id = $accountId;");
	modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW items_with_the_tags as 
								SELECT * from user_items 
								JOIN ItemTags ON user_items.item_id LIKE ItemTags.itemID
								JOIN Tags ON Tags.id LIKE ItemTags.tagID
								WHERE COALESCE(Tags.textValue, Tags.dateTimeValue) IN ($queryArrayString);");

	modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW items_with_tag_count as
								SELECT item_id, count(*) as match_count
								FROM items_with_the_tags
								GROUP BY item_id;");

	// Query returns selection of normal item rows that contain the specified tags
	// with an extra field "match_count" to show how many of the specified tags item contains
	$r = fetchMultipleRecordsByMakingSQLQuery(
								"
								SELECT * from items_with_tag_count
								JOIN user_items ON items_with_tag_count.item_id LIKE user_items.item_id
								WHERE items_with_tag_count.match_count = $numberOfQueryItems;
								"
								);
	return $r;
}


// Returns a set of the items which matched the input tags
function getItemsByTagsWithPartialMatches($queryArray, $accountId){

	//Turn query array into a string in the form: 'John', 'Newtown', 'coffee', etc
	$queryArrayString = "";
	foreach ($queryArray as $queryItem) {
		$queryArrayString = $queryArrayString."'".$queryItem."', "; 
	}
	// Remove trailing comma/space 
	$queryArrayString = rtrim($queryArrayString,", ");

	// Intermediate views
	modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW user_items as SELECT * from items where account_id = $accountId;");
	modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW items_with_the_tags as 
								SELECT * from user_items 
								JOIN ItemTags ON user_items.item_id LIKE ItemTags.itemID
								JOIN Tags ON Tags.id LIKE ItemTags.tagID
								WHERE Tags.textValue IN ($queryArrayString);");

	modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW items_with_tag_count as
								SELECT item_id, count(*) as match_count
								FROM items_with_the_tags
								GROUP BY item_id;");

	// Query returns selection of normal item rows that contain the specified tags
	// with an extra field "match_count" to show how many of the specified tags item contains
	$r = fetchMultipleRecordsByMakingSQLQuery(
								"
								SELECT * from items_with_tag_count
								JOIN user_items ON items_with_tag_count.item_id LIKE user_items.item_id;
								"
								);
	return $r;
}

?>