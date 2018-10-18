<?php

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/items_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/tags_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 

// Security
checkSecuredSessionOtherwiseDie();

$item_number = getIdTextFieldContentsFromCurrentClientRequest();
$item_text = getTodoTextFieldContentsFromCurrentClientRequest();

// echo(" item: ".$item_id);
// echo(" new text: ".$item_text);

// Get user id
$user_id = getAccountIDOfCurrentUser();
if (! $user_id){
    echo("Just testing with Bob account");
    $user_id = 2;
}

echo("      we're trying to change item: $item_number      ");

$item_id = getItemIdByItemNumber($item_number);

// Modify item text 
modifyItemText($item_text, $item_id);

echo(" modifyItemText($item_text, $item_id); ");

// Re-tag item
deleteAllTagsForItem($item_id);
tagItem($item_id, $item_text, $user_id);


?>