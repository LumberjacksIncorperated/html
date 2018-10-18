<?php

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/items_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 

// Security
checkSecuredSessionOtherwiseDie();

$item_id = getIdTextFieldContentsFromCurrentClientRequest();
$item_text = getTodoTextFieldContentsFromCurrentClientRequest();

echo(" item: ".$item_id);
echo(" new text: ".$item_text);

// Modify item text
modifyItemText($item_text, $item_id);

// Re-tag item



?>