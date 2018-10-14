<?php
//--------------------------------------------------------------------------------------------------------------
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//--------------------------------------------------------------------------------------------------------------

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/../database_php_api.php';

//---------------------------------------- 
// CONSTANT DEFINITION
//----------------------------------------
define("SECONDS_UNTIL_A_SESSION_EXPIRES", 315360676);

//---------------------------------------- 
// INTERNAL FUNCTIONS
//---------------------------------------- 
    function _makeSanitizedSQLQueryToGetUnexpiredSessionRecordsForSessionKey($sessionKeyFromUser){
    	$sanitisedSessionKeyFromUser = sanitiseStringForSQLQuery($sessionKeyFromUser);
		$currentSessionRecord = fetchSingleRecordByMakingSQLQuery("SELECT * FROM Sessions WHERE TIMESTAMPDIFF(second, Sessions.last_session_renewal, CURRENT_TIMESTAMP) < ".SECONDS_UNTIL_A_SESSION_EXPIRES." AND session_key_sha1 = '".strval($sanitisedSessionKeyFromUser)."' LIMIT 1");
		return $currentSessionRecord;
    }

	function _retriveCurrentSessionRecord() {
		$currentSessionRecord = NULL;
		$sessionKeyFromUser = $_REQUEST['session_key'];
		if ($sessionKeyFromUser) {
			$currentSessionRecord = _makeSanitizedSQLQueryToGetUnexpiredSessionRecordsForSessionKey($sessionKeyFromUser);
		}
		return $currentSessionRecord;
	}

	function _renewCurrentSession() {
		$currentSessionRecord = _retriveCurrentSessionRecord();
		if ($currentSessionRecord) {
			$sessionKey = $currentSessionRecord['session_key_sha1'];
			modifyDataByMakingSQLQuery("UPDATE Sessions SET last_session_renewal = CURRENT_TIMESTAMP WHERE session_key_sha1 = '".strval($sessionKey)."'");
		}
	}

	function _deleteAllSessionsForAccountID($accountID) {
		modifyDataByMakingSQLQuery("DELETE FROM Sessions WHERE account_id = '".strval($accountID)."'");
	}

//---------------------------------------- 
// EXPOSED FUNCTTIONS
//---------------------------------------- 
	function _ensureThisIsASecuredSession() {
		$currentSessionRecord = _retriveCurrentSessionRecord();
		if ($currentSessionRecord) {
			_renewCurrentSession();
			return true;
		} else {
			return false;
		}
	}

	function checkSecuredSessionOtherwiseDie(){
		if (!_ensureThisIsASecuredSession()) {
			echo 'You must be logged into a secure session to use Tagnostic';
			die();
		}
	}

	function getSessionKeyForNewSessionWithUsernameAndPassword($username, $password) {
		$sessionKeyForNewSession = null;
		if ($username && $password) {
			$sanitisedUsername = sanitiseStringForSQLQuery($username);
			$passwordSha1 = sha1($password);
			$userAccountOnRecord = fetchSingleRecordByMakingSQLQuery("SELECT * FROM Accounts WHERE password_sha1 = '".$passwordSha1."' AND username = '".$sanitisedUsername."' LIMIT 1");
			$accountIDForSession = $userAccountOnRecord['account_id'];
			if ($accountIDForSession) {
				_deleteAllSessionsForAccountID($accountIDForSession);
				$sessionKeyForNewSession = sha1(strval(rand()));
				modifyDataByMakingSQLQuery("INSERT INTO Sessions (account_id, last_session_renewal, session_key_sha1) VALUES ('".$accountIDForSession."', CURRENT_TIMESTAMP, '".strval($sessionKeyForNewSession)."')");
			}
		}

		return $sessionKeyForNewSession;
	}

	function getAccountIDOfCurrentUser() {
		$currentAccountID = false;
		$currentSessionRecord = _retriveCurrentSessionRecord();
		if ($currentSessionRecord) {
			$currentAccountID = $currentSessionRecord['account_id'];
		}
		return $currentAccountID;
	}

	function logoutActiveAccountFromCurrentSession() {
		$accountIDOfCurrentUser = getAccountIDOfCurrentUser();
		if ($accountIDOfCurrentUser) {
			_deleteAllSessionsForAccountID($accountIDOfCurrentUser);
		}
	}

?>













