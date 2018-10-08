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
	// var_dump($nlpResult);
	
	if ($nlpResult === FALSE) { /* Handle error */ }
	if ($nlpResult === NULL) { return "dog"; }
	return $nlpResult;

}

function getDateTags($text){
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://api.dateparser.io/DMY/parse");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "[\"$text\"]");
	curl_setopt($ch, CURLOPT_POST, 1);

	$headers = array();
	$headers[] = "Accept: application/json";
	$headers[] = "X-Api-Key: df60929ddd5df0859aceb28d881b67a3dae25d242febf70afd0d51fb280240f1";
	$headers[] = "Content-Type: application/json";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close ($ch);

	return $result;

}


?>