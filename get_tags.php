<?php

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
// include_once dirname(__FILE__).'/_dependencies/core_procedures/todolist_php_api.php';
// include_once dirname(__FILE__).'/_dependencies/php_environment_php_api.php';
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 


    if (!ensureThisIsASecuredSession()) {
                echo 'Bad session';
    }

    $todoText = getTodoTextFieldContentsFromCurrentClientRequest();

    //Insert api key variable into script
    require_once '../../secure/api_key.php';

    $tag_text = "we need to do a highly retarded 4920 assignment Wayne Wobke";
    $requestURL = "https://language.googleapis.com/v1/documents:analyzeEntities?key=$google_api_key";

    $postData = array('document' => array('type' => 'PLAIN_TEXT', 'language' => 'EN', 'content' => $todoText),'encodingType' => 'UTF8');
//     $requestBody = "{
//           \"document\":{
//                 \"type\":\"PLAIN_TEXT\",
//                    \"language\": \"EN\",
//                        \"content\":\"we need to do a highly retarded 4920 assignment Wayne Wobke\" },
// \"encodingType\":\"UTF8\"
//      }";
     
$options = array(
    'http' => array(
                 'header'  => "Content-type: application/json\r\n",
                         'method'  => 'POST',
                                 'content' => json_encode($postData)
     )
);

$context  = stream_context_create($options);
$result = file_get_contents($requestURL, false, $context);
if ($result === FALSE) { /* Handle error */ }

echo($result);

?>
