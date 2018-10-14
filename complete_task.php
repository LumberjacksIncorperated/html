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

// Security
checkSecuredSessionOtherwiseDie();

$id_of_tag = getTagIDTextFieldContentsFromCurrentClientRequest();

toggleTaskCompletion($id_of_tag);

// echo "setting tag $id_of_tag to $flag";

?>