<?php
	
	//--------------------------------------------------------------------------------------------------------------
// AUTHOR
// -------
// Dan
//--------------------------------------------------------------------------------------------------------------

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 


//---------------------------------------- 
// INTERNAL FUNCTIONS
//---------------------------------------- 


//Takes in a text string and returns tags in some kind of JSON string
function getTagsForText($text){

	//Insert api key variable into script
	require_once '../../secure/api_key.php';
	$api_key = $google_api_key;

	$requestURL = "https://language.googleapis.com/v1/documents:analyzeEntities?key=$api_key";

	$postData = array('document' => array('type' => 'PLAIN_TEXT', 'language' => 'EN', 'content' => $text),'encodingType' => 'UTF8');
	 
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

	if ($result === NULL) { return "dog"; }

	return $result;

}



?>