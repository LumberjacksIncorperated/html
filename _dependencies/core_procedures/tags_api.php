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
// INTERNAL FUNCTIONS
//----------------------------------------

function _createTagNameAssociation(){

	;
}

//----------------------------------------
// EXPOSED FUNCTIONS
//----------------------------------------


// -- Rudimentary "autocorrect" for tags
// -- e.g. ability to apply tag "UNSW" if "uni" is mentioned 
// DROP TABLE IF EXISTS `AssociatedNames`;
// CREATE TABLE AssociatedNames (
// tagID VARCHAR(36) references Tags(id),
// associatedName VARCHAR(100),
// userID VARCHAR(36) references Accounts(account_id),
// probability REAL,
// primary key (tagID, associatedName, userID)
// );
function updateTagText($tagText, $tagId, $userId){
    $tagText = sanitiseStringForSQLQuery($tagText);
    $tagId = sanitiseStringForSQLQuery($tagId);

    // Save old value of tag
    $oldTagName = fetchSingleRecordByMakingSQLQuery = fetchSingleRecordByMakingSQLQuery("SELECT textValue from Tags WHERE id LIKE \"$tagId\";");
    $oldTagName = $oldTagName['textValue'];

    modifyDataByMakingSQLQuery("UPDATE Tags SET textValue = \"$tagText\", timeModified = CURRENT_TIMESTAMP WHERE id LIKE \"$tagId\";");

    // "Learn" the change in tags
    modifyDataByMakingSQLQuery("INSERT INTO AssociatedNames (inputName, outputName, userID) VALUES (\"$oldTagName\", \"$tagText\", \"$userId\");");
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



// Find tags (tag type and id), given the text value of the tag
function getTagsByNameAndUser($tagText, $accountId){

	// Only items which belong to the given user
	modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW user_items as SELECT * from items where account_id = $accountId;");

	$r = fetchMultipleRecordsByMakingSQLQuery(
		"SELECT Tags.id as tagId, 
		(SELECT name from TagTypes where id = Tags.tagTypeID) as tagType
		from Tags
		JOIN ItemTags ON Tags.id LIKE ItemTags.tagID
		JOIN user_items ON user_items.item_id LIKE ItemTags.itemID
		WHERE COALESCE(Tags.textValue, Tags.dateTimeValue)
		LIKE \"$tagText\";"
	);

	return $r;
}





?>