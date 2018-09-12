<?php
//--------------------------------------------------------------------------------------------------------------
//
// PURPOSE
// -------
// To check if user is logged in
//
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//--------------------------------------------------------------------------------------------------------------

// Header needed by REACT
header("Access-control-allow-origin: *");

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/_dependencies/core_procedures/secured_session_php_api.php';

//---------------------------------------- 
// SCRIPT
//---------------------------------------- 
	if (!ensureThisIsASecuredSession()) {
		echo 'false';
	} else {
		echo 'true';
	}

?>



