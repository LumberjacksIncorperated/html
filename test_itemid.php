
<?php

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/tags_api.php';

//$itemIdToGet = getIdTextFieldContentsFromCurrentClientRequest();
//displaySingleItemById($itemIdToGet);

// DATE TAG
// 2018-11-20T00:00:00.000Z
addDateTag("2018-11-20T00:00:00.000Z", "mygreatid");




?>