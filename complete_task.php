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

//erm ideally authenticate as well
if (!ensureThisIsASecuredSession()) {
            echo 'Bad session';
}

$id_of_tag = getTagIDTextFieldContentsFromCurrentClientRequest();
$flag = getFlagContentsFromCurrentClientRequest();

// If no flags, we're marking the task as completed
if ($flag == ""){
	$flag = "true";
}

markTaskAsCompleted($id_of_tag, $flag);

echo "setting tag $id_of_tag to $flag";

?>