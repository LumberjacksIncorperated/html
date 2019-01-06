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
function getTopSixTagsForUser($userID){

        modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW user_items as
                                    SELECT * from items where account_id = \"$userID\"");

        modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW user_tags 
                                    AS SELECT tagID from ItemTags 
                                    JOIN user_items
                                    ON user_items.item_id like ItemTags.itemID;");

        modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW user_tag_names
                                    AS SELECT textValue from Tags
                                    JOIN user_tags 
                                    ON user_tags.tagID LIKE Tags.id
                                    WHERE NOT textValue LIKE 'Done?';");

        modifyDataByMakingSQLQuery("CREATE OR REPLACE VIEW top_user_tags
                                    AS SELECT textValue, count(textValue) as count
                                    from user_tag_names
                                    GROUP BY textValue
                                    ORDER BY count DESC;");

        $r = fetchMultipleRecordsByMakingSQLQuery("SELECT * from top_user_tags
                                                   LIMIT 6;");
        return $r;
	}

?>