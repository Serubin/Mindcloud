<?php
	/******************************************************************************
	 * index.php
	 * Author: Solomon Rubin, Michael Shullick
	 * 31 January 2015
	 * Index of api.mindcloud.io which handles requests made to the API.
	 ******************************************************************************/

	// Check that the user is logged in
	require_once "/var/www/api/include/db_config.php";
	require_once "/var/www/api/include/utils.php";

	// All CORS -- Security considerations?
	//header("Access-Control-Allow-Origin: *");
	//header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
	//header("Access-Control-Allow-Headers: *");

	// Valid application IDs
	/*$applications = array(
		"MOBILE_WEB",
		"MOBILE_ANDROID",
		"MOBILE_IOS",
		"DESKTOP");*/

	// Include model classes
	//require_once "/var/www/api/models/ItemObject.php";

	// Include Shoptimizer class
	//require_once "/var/www/api/Shoptimizer.php";

	try {

		// Handle on the encrypted data
		//$enc_request = $_REQUEST['enc_request'];

		// Get the app id of the request
		//$app_id = $_REQUEST['app_id'];

		// Check that the app id exists in the list of apps
		//if (!in_array($app_id, $applications)) {
		//	throw new exception('Application does not exist.');
		//}

		// Continue to decrypt the data
		//$params = decryptData($enc_request, file_get_contents("key/mobileweb_private.pem"));
		//error_log(json_encode("Also got: " . $params));

		$params = $_REQUEST;

		//error_log(json_encode($_REQUEST));
		//error_log("Https: " . $_SERVER['HTTPS']);

		// Check that the request data is valid
		if ($params == false || isset($params['controller']) == false || isset($params['action']) == false)
			throw new Exception ("Request not valid.");

		// Get correctly formatted controller designation:
		// First letter capitalized only
		$controller = ucfirst(strtolower($params['controller']));

		// Get the action formatted correctly
		$action = strtolower($params['action']) . $controller;

		// Check if the controller is valid
		if (file_exists("/var/www/api/controllers/{$controller}.php")) {
			include_once("/var/www/api/controllers/{$controller}.php");
		} else {
			throw new Exception('Invalid controller.');
		}

		// insantiate an instance of the controller;
		$controller = new $controller($params, $mysqli);

		// Check if the action is valid
		if (method_exists($controller, $action) === false) {
			throw new Exception('Invalid action.');
		}

		// A valid request has been made, so start the user session
		sec_session_start();

		// execute
		$result['data'] = $controller->$action();

		if
		// The request itself was valid
		$result['success'] = true;

	}
	catch (Exception $e) {
		$result = Array();
		$result['success'] = false;
		$result['error'] = $e->getMessage();
	}
	
	// return the result
	echo json_encode($result);
	exit();