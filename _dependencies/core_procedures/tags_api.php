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
include_once dirname(__FILE__).'/todolist_php_api.php';


//----------------------------------------
// INTERNAL FUNCTIONS
//----------------------------------------

function _createTagNameAssociation(){

	;
}

function getTagType($tagId){
    $tag = fetchSingleRecordByMakingSQLQuery("SELECT tagTypeID from Tags WHERE id LIKE \"$tagId\";");
    $tagTypeId = $tag['tagTypeID'];
    $tagType = fetchSingleRecordByMakingSQLQuery("SELECT name from TagTypes WHERE id = $tagTypeId");
    $tagType = $tagType['name'];
    return $tagType;
}


//----------------------------------------
// EXPOSED FUNCTIONS
//----------------------------------------


function addManualTag($itemID, $tagText, $userId){

    //ideally, check that the user owns the item
    //TODO

    // Try to infer tag type

    echo("^^^^^^^^^ addManualTag($itemID, $tagText, $userId) ^^^^^^^^^^^");

    //E.g. if someone types "tomorrow", they should get the date
    $nlpDates = findNlpDateTagsForItem($itemID, $todoText);
    $customDates = findCustomDateTagsForItem($itemID, $todoText);
    $datesArray = array_merge($nlpDates, $customDates);

    //For now, if we find a date in the query string, we assume a date was meant by the user.
    if ($datesArray != NULL){

        $firstMatch = $datesArray[0];
        $dateString = str_replace(".000","", $firstMatch);
        addDateTagForItem($itemID, $firstMatch);

        echo("&&&&&&&& it's a date! addDateTagForItem($itemID, $firstMatch);");

    }
    // If no date found, treat it as a normal tag
    else {
        $tags = getTagsForText($todoText);  //Actual NLP function
        $mytags = json_decode($tags, true);
        $tagType = strtolower($mytags['entities'][0]['type']);
        $tagID = uuidv4(openssl_random_pseudo_bytes(16));
        addTag($tagText, $tagType, $tagID);
        addTagForItem($itemID, $tagID);


        echo("&&&&&&&& it's a normal tag! type: $tagType, text: $tagText);");
    }

}


// Delete all tags for item
// TABLE `ItemTags`;
// itemID VARCHAR(36) references Items(item_id),
// tagID VARCHAR(36) references Tags(id),
function deleteAllTagsForItem($itemID){

    echo(" deleteAllTagsForItem($itemID) ");

    modifyDataByMakingSQLQuery("DELETE from ItemTags WHERE itemID LIKE \"$itemID\";");

    //TODO: delete tags as well
}

// Update tag id manually on site
function updateDateTag($tagId, $dateString){

    // Replace '/' with '-', this way Australian/European date format is assumed
    // See http://php.net/manual/en/function.strtotime.php
    $dateString = preg_replace('/\//', '-', $dateString);

    // https://stackoverflow.com/questions/22061723/regex-date-validation-for-yyyy-mm-dd/22061800
    if (preg_match("/^(0?[1-9]|[12][0-9]|3[01])\-(0?[1-9]|1[012])\-\d{4}$/", $dateString)){
        echo "valid";
    } else {
        return "Error: invalid date format";
    }

    $dateValue = date('Y-m-d', strtotime($dateString));
    $result = modifyDataByMakingSQLQuery("UPDATE Tags SET dateTimeValue = \"$dateValue\" WHERE id LIKE \"$tagId\";");
    return "success";
}



// -- Rudimentary "autocorrect" for tags
// -- e.g. ability to apply tag "UNSW" if "uni" is mentioned 
function updateTagText($tagText, $tagId, $userId){

    $tagText = sanitiseStringForSQLQuery($tagText);
    $tagId = sanitiseStringForSQLQuery($tagId);

    // Get details of tag to change
    $originalTag = fetchSingleRecordByMakingSQLQuery("SELECT * from Tags WHERE id LIKE \"$tagId\";");
    
    // Deal with dates
    $tagType = getTagType($tagId);
    if ($tagType == 'date'){
        $successStatus = updateDateTag($tagId, $tagText);
        return $successStatus;
    }

    // Save old value of tag for learning association
    $oldTagName = $originalTag['textValue'];

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
