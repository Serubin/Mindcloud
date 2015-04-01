<?php
/******************************************************************************
 * Notification.php
 * Author: Michael Shullick
 * ©mindcloud
 * 1 February 2015
 * Controller for User-related actions.
 * !!! NOT YET ADAPTED !!!
 ******************************************************************************/

// relative to index.php
require_once "models/UserObject.php";

class Notification
{
	private $_params;
	private $_mysqli;

	// Constructor
	public function __construct($params, $mysqli) {
		$this->_params = $params;
		$this->_mysqli = $mysqli;
	}

	/* createNotification()
	 * Creates a NotifcationObject, sets the vars, and creates the user in the database.
	 * Returns true on success or error on fail.
	 */
	public function createNotification() {

	}

	public function loadNotification(){

	}

	/**
	 * createStreamNotification()
	 * Creates a new pusher stream based on users unique notification hash.
	 */
	public function createStreamNotification(){

	}
	public function fetchAllUserNotification(){

	}
}

