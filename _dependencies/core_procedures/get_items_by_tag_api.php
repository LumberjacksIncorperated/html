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

//----------------------------------------
// INTERNAL FUNCTIONS
//----------------------------------------


//----------------------------------------
// FUNCTIONS
//----------------------------------------


// Returns a set of the items which matched the input tags
// Ordered by how many were matched
function getItemsByTags($queryArray, $accountId){

	$size = count($queryArray);

	//Turn array into a string in the form: 'John', 'Newtown', 'coffee',
	$queryArrayString = "";
	foreach ($queryArray as $queryItem) {
		$queryArrayString = $queryArrayString."'".$queryItem."', "; 
		// $queryArrayString = $queryArrayString.$queryItem; 
		// $queryArrayString = $queryArrayString."'"; 
		// $queryArrayString = $queryArrayString.","; 
		// $queryArrayString = $queryArrayString." "; 
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