<?php
	$_REQUEST['test_environment'] = 'true';

	include_once dirname(__FILE__).'/my_application_database_php_api.php';

	$table = $_SERVER['argv'][1];
	$column = $_SERVER['argv'][2];
	$indexColumnName = $_SERVER['argv'][3];
	$indexColumnValue = $_SERVER['argv'][4];

	$theDataFetched = fetchSingleRecordByMakingSQLQuery("SELECT * FROM ".$table." WHERE ".$indexColumnName."=".$indexColumnValue);
	echo $theDataFetched[$column];

?>