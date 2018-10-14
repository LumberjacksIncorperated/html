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
    function _createTaskWithoutTagsForSingleEntry($itemEntry) {
        $todoEntry = "".$itemEntry['item_text'];
        $entryTime = "".$itemEntry['time_posted'];
        $itemID = "".$itemEntry['item_id'];
        $itemNumber = "".$itemEntry['itemNumber'];
        $task = array("task" => $todoEntry, "created_at" => $entryTime, "item_id" => $itemNumber, "tag_list" => array());
        return $task;
    }

    function _createTaskFromItemListEntryForSingleEntry($itemEntry) {
        $itemID = "".$itemEntry['item_id'];
        $task = _createTaskWithoutTagsForSingleEntry($itemEntry);
        $itemTags = getTagsForItem($itemID);
        foreach ($itemTags as $itag) {
            array_push($task['tag_list'], array("textValue" => $itag['textValue'], "tagType" => $itag['tagType'], "tagID" => $itag['id']));
        }
        return $task;
    }

    function _displayTaskAsJson($task) {
        echo (json_encode($task));
    }

//----------------------------------------
// EXPOSED FUNCTIONS
//----------------------------------------


    // Display a single item based on an item_id
	function displaySingleItemById($itemNum){
		$sanitisedItemNum = sanitiseStringForSQLQuery($itemNum);
		$item = fetchSingleRecordByMakingSQLQuery("SELECT * from items WHERE itemNumber = $sanitisedItemNum;");
		$taskForItem = _createTaskFromItemListEntryForSingleEntry($item);
		_displayTaskAsJson($taskForItem);
	}

    // Get all items for a given user
    function getAllItemsForUser($accountId) {
        $r = fetchMultipleRecordsByMakingSQLQuery("SELECT * FROM items WHERE account_id = $accountId");
        return $r;
    }

    // Modify item text
    // We're doing this by item number, which is effectively a second ID
    function modifyItemText($itemText, $itemNum){
        echo("modifyDataByMakingSQLQuery(\"UPDATE items SET item_text = \"$itemText\", time_modified = CURRENT_TIMESTAMP WHERE itemNumber LIKE \"$itemNum\";");

        modifyDataByMakingSQLQuery("UPDATE items SET item_text = \"$itemText\", time_modified = CURRENT_TIMESTAMP WHERE itemNumber LIKE $itemNum;");
    }

    // Delete item
    // We're doing this by item number, which is effectively a second ID
    function deleteItemWithId($num) {
            modifyDataByMakingSQLQuery("DELETE FROM items WHERE itemNumber LIKE \"$num\";");
    }



?>