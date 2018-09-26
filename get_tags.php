<?php

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';

//---------------------------------------- 
// SCRIPT
//----------------------------------------  
    if (!ensureThisIsASecuredSession()) {
                echo 'Bad session';
     }


     $requestURL = "https://language.googleapis.com/v1/documents:analyzeEntities?key=AIzaSyBQJ_GKPMbk0Bo9xUZGp_FCLBzwSS_6wYA";

     $requestBody = "{
          \"document\":{
                \"type\":\"PLAIN_TEXT\",
                   \"language\": \"EN\",
                       \"content\":\"we need to do a highly retarded 4920 assignment Wayne Wobke\" },
\"encodingType\":\"UTF8\"
     }";
     
$options = array(
    'http' => array(
                 'header'  => "Content-type: application/json\r\n",
                         'method'  => 'POST',
                                 'content' => $requestBody
     )
);

$context  = stream_context_create($options);
$result = file_get_contents($requestURL, false, $context);
if ($result === FALSE) { /* Handle error */ }

echo($result);






?>
