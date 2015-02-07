<?php
/******************************************************************************
 * index.php
 * Author: Solomon Rubin, Michael Shullick
 * 31 January 2015
 * © mindcloud
 * Index of api.mindcloud.io which handles requests made to the API.
 ******************************************************************************/

	// Check that the user is logged in
	require_once "/var/www/api/include/db_config.php";
	require_once "/var/www/api/include/error.php"
	require_once "/var/www/api/include/utils.php";

	try {

		$params = $_REQUEST;

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

		// execute
		$result['data'] = $controller->$action();

		if ($result['data'] instanceof Exception) {
			throw new Exception($result['data']);
		}

		// The request itself was valid
		$result['success'] = true;

	}
	catch (Exception $e) {
		$result = Array();
		$result['success'] = false;
		$result['error'] = $e->stringify();
	}
	
	// return the result
	echo json_encode($result);
	exit();