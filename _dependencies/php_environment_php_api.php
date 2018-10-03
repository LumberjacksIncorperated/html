<?php
//--------------------------------------------------------------------------------------------------------------
// AUTHOR
// -------
// Lumberjacks Incorperated (2018) this is a test
//--------------------------------------------------------------------------------------------------------------

//---------------------------------------- 
// INTERNAL FUNCTTIONS
//---------------------------------------- 
    function _getFieldContentsFromCurrentClientRequestWithParameterKey($parameterKey) {
        $fieldContentsOfCurrentHTTPRequest = '';
        if (isset($_REQUEST[$parameterKey]))
            $fieldContentsOfCurrentHTTPRequest = $_REQUEST[$parameterKey];
        return $fieldContentsOfCurrentHTTPRequest;
    }

    //function _setPHPEnvironemntConfiguration($pHPEnvironmentConfiguration) {
    //    $GLOBAL['php_environment_configuration'] = $pHPEnvironmentConfiguration;
    //}

    function _getEnvironmentForProduction() {
        $productionConfiguration = new PHPEnvironmentConfiguration();
        $productionConfiguration->mainDatabaseName = 'tagnostic2';
        return $productionConfiguration;
        //_setPHPEnvironemntConfiguration($productionConfiguration);
    }
    function _getEnvironmentForTesting() {
        $testConfiguration = new PHPEnvironmentConfiguration();
        $testConfiguration->mainDatabaseName = 'tagnostic2';
        #$testConfiguration->mainDatabaseName = 'my_application_test_database';
        return $testConfiguration;
        //_setPHPEnvironemntConfiguration($testConfiguration);
    }
    
//---------------------------------------- 
// EXPOSED FUNCTIONS
//---------------------------------------- 

    class PHPEnvironmentConfiguration {
        var $mainDatabaseName;
        function __construct() {}    
    }

    function getPHPEnvironmentConfiguration() {
        $environment = NULL;
        if ($_REQUEST['test_environment']) {
            $environment = _getEnvironmentForTesting();
        } else {
            $environment = _getEnvironmentForProduction();
        }
        return $environment;
    }

    function getConnectedClientIPAddress() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function getMessageFieldContentsFromCurrentClientRequest() {
        return _getFieldContentsFromCurrentClientRequestWithParameterKey('message');
    }

    function getPasswordFieldContentsFromCurrentClientRequest() {
        return _getFieldContentsFromCurrentClientRequestWithParameterKey('password');
    }

    function getUsernameFieldContentsFromCurrentClientRequest() {
        return _getFieldContentsFromCurrentClientRequestWithParameterKey('username');
    }

    function getDestinationUsernameFieldContentsFromCurrentClientRequest() {
        return _getFieldContentsFromCurrentClientRequestWithParameterKey('destination_username');
    }

    function getTodoTextFieldContentsFromCurrentClientRequest() {
        return _getFieldContentsFromCurrentClientRequestWithParameterKey('todoText');
    }
    function getIdTextFieldContentsFromCurrentClientRequest() {
        return _getFieldContentsFromCurrentClientRequestWithParameterKey('itemId');
    }
    function getTagIDTextFieldContentsFromCurrentClientRequest() {
        return _getFieldContentsFromCurrentClientRequestWithParameterKey('tagId');
    }
    function timeTextFieldContentsFromCurrentClientRequest() {
        return _getFieldContentsFromCurrentClientRequestWithParameterKey('time');
    }
    // function placeTextFieldContentsFromCurrentClientRequest() {
    //     return _getFieldContentsFromCurrentClientRequestWithParameterKey('place');
    // }
    // function peopleTextFieldContentsFromCurrentClientRequest() {
    //     return _getFieldContentsFromCurrentClientRequestWithParameterKey('people');
    // }
    // function topicTextFieldContentsFromCurrentClientRequest() {
    //     return _getFieldContentsFromCurrentClientRequestWithParameterKey('topic');
    // }
?>
