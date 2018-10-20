<?php
//--------------------------------------------------------------------------------------------------------------
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//--------------------------------------------------------------------------------------------------------------

include_once dirname(__FILE__).'/php_environment_php_api.php';

//---------------------------------------- 
// INTERNAL FUNCTTIONS
//---------------------------------------- 
	function _makeConnectionToMyApplicationDatabase() {
		$connectionToMyApplicationDatabase = new mysqli("127.0.0.1", "root", "MenBell1!", getPHPEnvironmentConfiguration()->mainDatabaseName);
		if ($connectionToMyApplicationDatabase->connect_errno) {
			$connectionToMyApplicationDatabase = NULL;
		}
		return $connectionToMyApplicationDatabase;
	}

	function _closeConnectionToMyApplicationDatabase($myApplicationDatabase) {
		if ($myApplicationDatabase) {
			$myApplicationDatabase->close();
		}
	}

	function _fetchDataFromQueryResult($queryResult) {
		$fetchedData = NULL;
		if ($queryResult) {
			$fetchedData = array();
			foreach ($queryResult as $row) {
    			array_push($fetchedData, $row);
			}
			$queryResult->close();
		}	
		return $fetchedData;
	}

	function _fetchDataByMakingSQLQuery($queryToFetchData) {
		$connectionToMyApplicationDatabase = _makeConnectionToMyApplicationDatabase();
		$resultOfQuery = $connectionToMyApplicationDatabase->query($queryToFetchData);
		$fetchedData = _fetchDataFromQueryResult($resultOfQuery);
		_closeConnectionToMyApplicationDatabase($connectionToMyApplicationDatabase);
		return $fetchedData;
	}

//---------------------------------------- 
// EXPOSED FUNCTIONS
//---------------------------------------- 
	function fetchSingleRecordByMakingSQLQuery($queryToFetchSingleRecord) {
		$fetchedData = _fetchDataByMakingSQLQuery($queryToFetchSingleRecord);
		if ($fetchedData) {
			return array_pop(array_reverse($fetchedData));
		} else {
			return NULL;
		}
	}

	function fetchMultipleRecordsByMakingSQLQuery($queryToFetchSingleRecord) {
		$fetchedData = _fetchDataByMakingSQLQuery($queryToFetchSingleRecord);
		if ($fetchedData) {
			return $fetchedData;
		} else {
			return NULL;
		}
	}

	function modifyDataByMakingSQLQuery($queryToModifyData) {
		$connectionToMyApplicationDatabase = _makeConnectionToMyApplicationDatabase();
		$resultOfQuery = $connectionToMyApplicationDatabase->query($queryToModifyData);
		_closeConnectionToMyApplicationDatabase($connectionToMyApplicationDatabase);
		return $resultOfQuery;
	}

	function insertDataBySQLQueryAndReturnIDOfGeneratedRecordOnSuccess($queryToModifyData) {
		$connectionToMyApplicationDatabase = _makeConnectionToMyApplicationDatabase();
		$resultOfQuery = $connectionToMyApplicationDatabase->query($queryToModifyData);
		$returnedResult = false;
		if ($resultOfQuery) {
			$returnedResult = $connectionToMyApplicationDatabase->insert_id;
		}
		_closeConnectionToMyApplicationDatabase($connectionToMyApplicationDatabase);
		return $returnedResult;
	}

	function sanitiseStringForSQLQuery($unsanitisedInput) {
		$connectionToMyApplicationDatabase = _makeConnectionToMyApplicationDatabase();
		$sanitisedInput = $connectionToMyApplicationDatabase->real_escape_string($unsanitisedInput);
		_closeConnectionToMyApplicationDatabase($connectionToMyApplicationDatabase);
		return $sanitisedInput;
	}

?>
