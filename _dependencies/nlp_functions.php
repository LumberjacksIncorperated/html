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
//TODO: deal with escape characters
function getTagsForText($text){

	$api_key =  _getGoogleApiKey();
	$requestURL = "https://language.googleapis.com/v1/documents:analyzeEntities?key=$api_key";
	$requestPacket = _createRequestPacketForData($text);
	$requestPacketStream  = stream_context_create($requestPacket);
	$nlpResult = file_get_contents($requestURL, false, $requestPacketStream);
	
	if ($nlpResult === FALSE) { /* Handle error */ }
	if ($nlpResult === NULL) { return "dog"; }
	return $nlpResult;

}



?>