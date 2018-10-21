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

function _getGoogleApiKey() {
	require_once '../../secure/api_key.php';
	$api_key = $google_api_key;
	return $api_key;
}

function _createRequestPacketForData($text) {
	$postData = array('document' => array('type' => 'PLAIN_TEXT', 'language' => 'EN', 'content' => $text),'encodingType' => 'UTF8'); 
	$requestPacket = array(
		'http' => array(
	            'header'  => "Content-type: application/json\r\n",
	            'method'  => 'POST',
	            'content' => json_encode($postData)
	 	)
	);
	return $requestPacket;
}

//Takes in a text string and returns tags in some kind of JSON string
function getTagsForText($text){

	$api_key =  _getGoogleApiKey(); 
	$requestURL = "https://language.googleapis.com/v1/documents:analyzeEntities?key=$api_key";
	$requestPacket = _createRequestPacketForData($text);	var_dump($requestPacket);
	$requestPacketStream  = stream_context_create($requestPacket);
	$nlpResult = file_get_contents($requestURL, false, $requestPacketStream);
	var_dump($nlpResult);
	
	if ($nlpResult === FALSE) { /* Handle error */ }
	if ($nlpResult === NULL) { return "dog"; }
	return $nlpResult;

}

function getDateTags($text){

	$curlCommandString = 'curl -X POST "https://api.dateparser.io/DMY/parse" -H "accept: applicatn/json" -H "X-API-KEY: df60929ddd5df0859aceb28d881b67a3dae25d242febf70afd0d51fb280240f1" -H "Content-Type: application/json" -d "[ \"'.$text.'\"]"';
    $output = shell_exec("$curlCommandString");

	return $output;

}


?>