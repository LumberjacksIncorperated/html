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

function getItemsByTags($queryArray){


	// We need to get 




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