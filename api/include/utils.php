<?php
/******************************************************************************
 * utils.php
 * Author: Michael Shullick
 * 2 January 2015
 * © mindcloud
 * Offers a small library of utility functions for the api server.
 ******************************************************************************/

/* sec_session_start
 * This function will create / resume a session on every page it is called. 
 * If the variables of the past session were set, then it will retain
 * them in the new session.
 *
 * NOTES FOR FINAL DEPLOYMENT:
 * make sure session.cookie_secure is set in php.ini
 * Test on all browsers
 * set cookie domain in php.ini?
 * be sure regenerate session id to true
 *
 * ===== No longer being used ===== 
 */
function sec_session_start() {

	// Declare the name of the session*
	$session_name = "shopt";

	// Names the session	
	session_name($session_name);
	
	// Requires that cookies set can only be accessed by this server
	$httponly = TRUE;
	
	// requries https connection
	$secure = TRUE;
	
	// Requires that the session only uses cookies
	if (ini_set('session.use_only_cookies', 1) == FALSE) {
		header("Location: ../error.php?err=Could not instantiate safe session (ini_set)");
		exit();
	}

	// Why get previously set?
	$cookieParams = session_get_cookie_params();
	//error_log(json_encode($cookieParams));
	session_set_cookie_params($cookieParams["lifetime"],
		$cookieParams['path'],
		$cookieParams['domain'],
		$secure,
		$secure);
	
	// Begin session
	session_start();

	// Deletes old session
	// WARNING: To keep session variables, either use https or 
	// set session.cookie_secure to false in php.ini
	session_regenerate_id(true);
	//setcookie($session_name, session_id());
}