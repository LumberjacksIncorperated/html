<?php
//--------------------------------------------------------------------------------------------------------------
// AUTHOR
// -------
// Lumberjacks Incorperated (2018)
//--------------------------------------------------------------------------------------------------------------

//---------------------------------------- 
// INCLUDES
//---------------------------------------- 
include_once dirname(__FILE__).'/../my_application_database_php_api.php';


//---------------------------------------- 
// INTERNAL FUNCTTIONS
//---------------------------------------- 
	function _addMessageToMessagesStorageWithMessageTextAndIPAddressOriginAndReturnIDOfNewMessageOnSuccess($messageText, $ipAddressOriginOfMessage) {
		$idOfNewMessageCreatedIfSuccessful = false;
		if ($messageText && $ipAddressOriginOfMessage) {
			$sanitisedMessageText = sanitiseStringForSQLQuery($messageText);
			$sanitisedIpAddressOriginOfMessage = sanitiseStringForSQLQuery($ipAddressOriginOfMessage);
			$idOfNewMessageCreatedIfSuccessful = insertDataBySQLQueryAndReturnIDOfGeneratedRecordOnSuccess("INSERT INTO messages (message_text, ip_address_origin_of_message) VALUES ('".$sanitisedMessageText."', '".$sanitisedIpAddressOriginOfMessage."')");
		}
		return $idOfNewMessageCreatedIfSuccessful;
	}

	function _getAccountIDForUsername($theUsernameInQuestion) {
		$accountRecordforTheUsernameInQuestion = fetchSingleRecordByMakingSQLQuery("SELECT * FROM accounts WHERE username = '".$theUsernameInQuestion."' LIMIT 1");
		if ($accountRecordforTheUsernameInQuestion) {
			return $accountRecordforTheUsernameInQuestion['account_id'];
		} else {
			return NULL;
		}
	}

	function _addLinkForMessageIDAsSentMessageFromOriginToDestinationAccountID($messageIDOfMessage, $accountIDOfOrigin, $accountIDOfDestination) {
		if ($messageIDOfMessage && $accountIDOfOrigin && $accountIDOfDestination) {
			modifyDataByMakingSQLQuery("INSERT INTO sent_messages (message_id, origin_account_id, destination_account_id) VALUES ('".$messageIDOfMessage."', '".$accountIDOfOrigin."', '".$accountIDOfDestination."')");
		}
	}

	function _addMessageToMessageStorageForMessageDestinationUsernameAndIPAddressOfOrigin($sanitisedMessageText, $sanitisedDestinationUsername, $ipAddressOriginOfMessage) {
		$messageIDOfNewMessage = _addMessageToMessagesStorageWithMessageTextAndIPAddressOriginAndReturnIDOfNewMessageOnSuccess($sanitisedMessageText, $ipAddressOriginOfMessage);
		$messageWasSuccessfullyCreated = (!!$messageIDOfNewMessage);
		if ($messageWasSuccessfullyCreated) {
			$accountIDOfCurrentUser = getAccountIDOfCurrentUser();
			$accountIDOfRecipient = _getAccountIDForUsername($sanitisedDestinationUsername);
			_addLinkForMessageIDAsSentMessageFromOriginToDestinationAccountID($messageIDOfNewMessage, $accountIDOfCurrentUser, $accountIDOfRecipient);
		}
	}

//---------------------------------------- 
// EXPOSED FUNCTTIONS
//---------------------------------------- 
	function sendMessageFromCurrentUserToDestinationUsernameWithMessageText($destinationUsername, $messageText) {
		if ($messageText && $destinationUsername) {
			$sanitisedMessageText = sanitiseStringForSQLQuery($messageText);
			$sanitisedDestinationUsername = sanitiseStringForSQLQuery($destinationUsername);
			$ipAddressOriginOfMessage = getConnectedClientIPAddress();
			_addMessageToMessageStorageForMessageDestinationUsernameAndIPAddressOfOrigin($sanitisedMessageText, $sanitisedDestinationUsername, $ipAddressOriginOfMessage);
		}
	}

	function userDoesExistForUsername($usernameToCheckExists) {
		$foundAccountCorrespondingToUsername = false;
		if ($usernameToCheckExists) {
			$sanitisedUsernameToCheckExists = sanitiseStringForSQLQuery($usernameToCheckExists);
			$accountIDForUsername = _getAccountIDForUsername($sanitisedUsernameToCheckExists);
			if ($accountIDForUsername) {
				$foundAccountCorrespondingToUsername = true;
			}
		}
		return $foundAccountCorrespondingToUsername;
	}

	function getMostRecentReceivedMessages() {
		$accountIDOfCurrentUser = getAccountIDOfCurrentUser();
		if ($accountIDOfCurrentUser) {
			$mostRecentReceivedMessages = fetchMultipleRecordsByMakingSQLQuery("select messages.message_text as message_text, accounts.username as origin_username from sent_messages inner join messages on messages.message_id = sent_messages.message_id inner join accounts on sent_messages.origin_account_id = accounts.account_id where sent_messages.destination_account_id=".strval($accountIDOfCurrentUser)." order by sent_message_id desc limit 10");
			return $mostRecentReceivedMessages;
		} else {
			return NULL;
		}
	}


	

?>


