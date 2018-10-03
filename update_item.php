<?php

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 

//erm ideally authenticate as well
if (!ensureThisIsASecuredSession()) {
            echo 'Bad session';
}

$item_id = getIdTextFieldContentsFromCurrentClientRequest();
$item_text = getTodoTextFieldContentsFromCurrentClientRequest();

modifyItemText($item_text, $item_id);

?>