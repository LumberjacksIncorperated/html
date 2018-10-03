<?php

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
// include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';
include_once dirname(__FILE__).'/_dependencies/nlp_functions.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 

//usage: http://165.227.25.45/get_tags.php?todoText=%22hello%20there%20my%20name%20is%20Dan%22
//erm ideally authenticate as well

if (!ensureThisIsASecuredSession()) {
            echo 'Bad session';
}

$id_of_tag = getTagIDTextFieldContentsFromCurrentClientRequest();

markTaskAsCompleted($id_of_tag);

echo($id_of_tag);

// markItemAsCompleted($id_of_item);

// $result = getTagsForText($todoText);

// echo($result);

?>