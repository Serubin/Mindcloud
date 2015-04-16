<?php
session_start();
/******************************************************************************
 * index.php
 * Author: Solomon Rubin, Michael Shullick
 * 31 January 2015
 * © mindcloud
 * Index of api.mindcloud.io which handles requests made to the API.
 ******************************************************************************/

	// sessions
	//require_once "include/utils.php";
	//sec_session_start();

	// include db and errors
	require_once "include/db_config.php";
	require_once "include/error.php";

	// include user object
	require_once "models/UserObject.php";

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

		// authenticate requests that aren't for loggin in or registration
		// do it before $controller is still a string
		$user = new UserObject($mysqli);
		if (!(strtolower($controller) == "user" && ($params['action'] == "create" || $params['action'] == "login" || $params['action'] == "check" || $params['action'] == "verify"))) {
			if (!$user->loginCheck()) {
				throw new Exception('unauthorized request');
			}

			if(isset($params['uid']) && $params['uid'] == "SESSION") {
				$params['uid'] = $_SESSION['uid'];
			}
		}

		// Check if the controller is valid
		if (file_exists("controllers/{$controller}.php")) {
			include_once("controllers/{$controller}.php");
		} else {
			throw new Exception('Invalid controller: ' . $controller);
		}

		// insantiate an instance of the controller;
		$controller = new $controller($params, $mysqli);

		// Check if the action is valid
		if (method_exists($controller, $action) === false) {
			throw new Exception('Invalid action.' . $action);
		}

		// execute
		$result['data'] = $controller->$action();

		if ($result['data'] instanceof MindcloudException) {
			throw new Exception($result['data']);
		}

		// The request itself was valid
		$result['success'] = true;

	}
	catch (MindcloudException $e) {
		$result = Array();
		$result['success'] = false;
		$result['error'] = $e->getMessage();
		$result['trace'] = $e->stringify();
		error_log($e->stringify());
	}
	catch (Exception $e) {
		$result['success'] = false;
		$result['error'] = $e->getMessage();
		$result['trace'] = $e->stringify();
		error_log($e->stringify());
	}
	
	// return the result
	echo json_encode($result);
	exit();