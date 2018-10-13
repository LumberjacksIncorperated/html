<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To get messages of current user
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018) and Dan
//
//
// PARAMETERS: 'tagText', 'session_key'
//--------------------------------------------------------------------------------------------------------------

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/tags_api.php';

//---------------------------------------- 
// INTERNAL FUNCTIONS
//---------------------------------------- 

    function _createTagListArrayFromQueryResults($queryArray, $tagText){
        
        $returnArray = array();

        // If nothing was found
        if ($queryArray == NULL){
            array_push($returnArray, array("textValue" => $tagText, "tagID" => NULL, "tagType" => NULL));
        }
        // If something was found
        else {
            foreach ($queryArray as $queryResult) {
                array_push($returnArray, array("textValue" => $tagText, "tagID" => $queryResult['tagId'], "tagType" => $queryResult['tagType']));
            }
        }
        return $returnArray;
    }


    function _displayArrayAsJson($array) {
        echo (json_encode($array));
    }

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 	
	if (!ensureThisIsASecuredSession()) {
		echo 'Bad session';
	}

    // Parameters
    $tagText = getTagTextValueFieldContentsFromCurrentClientRequest();
    $userId = 2; //TODO

    // Query the DB
    $tagsArray = getTagsByNameAndUser($tagText, $userId); 

    // Make sure that the FE gets a predictable array type, not just null, even if DB query result is empty
    $prettyArrayWithNullValues = _createTagListArrayFromQueryResults($tagsArray, $tagText);

    // Echo the array as JSON
    _displayArrayAsJson($prettyArrayWithNullValues);

?>
