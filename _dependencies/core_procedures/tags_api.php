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

// Delete all tags for item
// TABLE `ItemTags`;
// itemID VARCHAR(36) references Items(item_id),
// tagID VARCHAR(36) references Tags(id),
function deleteAllTagsForItem($itemID){

    echo(" deleteAllTagsForItem($itemID) ");

    modifyDataByMakingSQLQuery("DELETE from ItemTags WHERE itemID = $itemID;");

    //TODO: delete tags as well
}


// -- Rudimentary "autocorrect" for tags
// -- e.g. ability to apply tag "UNSW" if "uni" is mentioned 
function updateTagText($tagText, $tagId, $userId){

    $tagText = sanitiseStringForSQLQuery($tagText);
    $tagId = sanitiseStringForSQLQuery($tagId);

    // Save old value of tag for learning association
    $oldTagName = fetchSingleRecordByMakingSQLQuery("SELECT textValue from Tags WHERE id LIKE \"$tagId\";");
    $oldTagName = $oldTagName['textValue'];

    modifyDataByMakingSQLQuery("UPDATE Tags SET textValue = \"$tagText\", timeModified = CURRENT_TIMESTAMP WHERE id LIKE \"$tagId\";");

    learnAssociationBetweenWords($oldTagName, $tagText, $userId);

}


// "Learn" the association between an old tag name and new tag text, for a given user
function learnAssociationBetweenWords($oldTagName, $tagText, $userId){

    // For now, if an existing association exists, we just override it with a new one
    $existingAssociation = fetchSingleRecordByMakingSQLQuery("SELECT * from AssociatedNames WHERE inputName LIKE \"$oldTagName\";");
    $existingAssociationInputName = $existingAssociation['inputName'];

    if ($existingAssociation){
    	echo("changing an existing association");
    	modifyDataByMakingSQLQuery("UPDATE AssociatedNames SET outputName = \"$tagText\" WHERE userID = \"$userId\" AND inputName LIKE \"$oldTagName\";");
    }
    else {
    	echo("making a new association");
	    modifyDataByMakingSQLQuery("INSERT INTO AssociatedNames (inputName, outputName, userID) VALUES (\"$oldTagName\", \"$tagText\", \"$userId\");");
    }
    echo("in function $oldTagName -> $tagText for $userId");
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
function deleteTag($tagID){
	modifyDataByMakingSQLQuery("DELETE from ItemTags WHERE tagID like \"$tagID\";");
    modifyDataByMakingSQLQuery("DELETE from Tags WHERE tagID like \"$tagID\";");
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
