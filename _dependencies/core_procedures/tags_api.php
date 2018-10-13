<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
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
// FUNCTIONS
//----------------------------------------

function updateTagText($tagText, $tagId){
    $tagText = sanitiseStringForSQLQuery($tagText);
    $tagId = sanitiseStringForSQLQuery($tagId);
    modifyDataByMakingSQLQuery("UPDATE Tags SET textValue = \"$tagText\", timeModified = CURRENT_TIMESTAMP WHERE id LIKE \"$tagId\";");
}

// DATE TAG
// 2018-11-20T00:00:00.000Z
function addDateTag($dateString, $tagID){
    $tagTypeID = fetchSingleRecordByMakingSQLQuery("SELECT id from TagTypes WHERE name LIKE \"date\";");
    $tagTypeNumber = $tagTypeID['id'];
    $dateValue = date($dateString);
    modifyDataByMakingSQLQuery("INSERT INTO Tags (id, tagTypeID, dateTimeValue) 
                                    VALUES (\"$tagID\", $tagTypeNumber, $dateValue);");

}

// DELETE TAG
// TABLE `ItemTags`;
// itemID VARCHAR(36) references Items(item_id),
// tagID VARCHAR(36) references Tags(id),
function deleteTag($tagID, $itemID){
	modifyDataByMakingSQLQuery("DELETE from ItemTags WHERE itemID like \"$itemID\" AND tagID like \"$tagID\";");
}

function getTagsByNameAndUser($tagText, $accountId){

	echo(" tag text is $tagText, account id $accountId is < that");

	// modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW matching_tags as
	// 							SELECT * from Tags
	// 							WHERE COALESCE(Tags.textValue, Tags.dateTimeValue) 
	// 							LIKE \"$tagText\"");

	modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW user_items as SELECT * from items where account_id = $accountId;");

	$r = fetchMultipleRecordsByMakingSQLQuery(

		"SELECT * from Tags
		JOIN ItemTags ON Tags.id LIKE ItemTags.tagID
		JOIN user_items ON user_items.item_id LIKE ItemTags.itemID
		WHERE COALESCE(Tags.textValue, Tags.dateTimeValue)
		LIKE \"$tagText\";");

	return $r;
}





?>