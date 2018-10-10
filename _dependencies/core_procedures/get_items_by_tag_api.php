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

	echo(" account id is $accountId ");

	$size = count($queryArray);
	echo("the size is $size");

	//Turn array into a string in the form: 'John', 'Newtown', 'coffee'
	$queryArrayString = "";
	foreach ($queryArray as $queryItem) {
		$queryArrayString = $queryArrayString."\'"; echo("  >".$queryArrayString."<   ");
		$queryArrayString = $queryArrayString.$queryItem; echo("  >".$queryArrayString."<   ");
		$queryArrayString = $queryArrayString."\'"; echo("  >".$queryArrayString."<   ");
		$queryArrayString = $queryArrayString.","; echo("  >".$queryArrayString."<   ");
		$queryArrayString = $queryArrayString." "; echo("  >".$queryArrayString."<   ");
	}
	echo("-------".$queryArrayString."----------");
	$queryArrayString = rtrim($queryArrayString,", ");
	echo(" queryArrayString = ".$queryArrayString);

	$r = fetchMultipleRecordsByMakingSQLQuery(

	"CREATE OR REPLACE VIEW user_items as SELECT * from items where account_id = $accountId;

	CREATE OR REPLACE VIEW items_with_the_tags as 
	SELECT * from user_items 
	JOIN ItemTags ON user_items.item_id LIKE ItemTags.itemID
	JOIN Tags ON Tags.id LIKE ItemTags.tagID
	WHERE Tags.textValue IN ('coffee');

	CREATE OR REPLACE VIEW items_with_tag_count as
	SELECT item_id, count(*) as match_count
	FROM items_with_the_tags
	GROUP BY item_id;

	SELECT * from items_with_tag_count
	JOIN user_items ON items_with_tag_count.item_id LIKE user_items.item_id;
	"
	);

	$c = count($r);
	echo("the size of r is $c");
	return $r;
}





// function updateTagText($tagText, $tagId){
//     $tagText = sanitiseStringForSQLQuery($tagText);
//     $tagId = sanitiseStringForSQLQuery($tagId);
//     modifyDataByMakingSQLQuery("UPDATE Tags SET textValue = \"$tagText\", timeModified = CURRENT_TIMESTAMP WHERE id LIKE \"$tagId\";");
// }

// // DATE TAG
// // 2018-11-20T00:00:00.000Z
// function addDateTag($dateString, $tagID){
//     $tagTypeID = fetchSingleRecordByMakingSQLQuery("SELECT id from TagTypes WHERE name LIKE \"date\";");
//     $tagTypeNumber = $tagTypeID['id'];
//     $dateValue = date($dateString);
//     modifyDataByMakingSQLQuery("INSERT INTO Tags (id, tagTypeID, dateTimeValue) 
//                                     VALUES (\"$tagID\", $tagTypeNumber, $dateValue);");

// }


?>