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
		try {
			if(!isset($this->_params['uid'], $this->_params['url'], $this->_params['message'])) {
				throw new UserException("unset vars: uid, url, message", __FUNCTION__);
			}

			$uid = filter_var($this->_params['uid'], FILTER_SANITIZE_NUMBER_INT)

			$url = filter_var($this->_params['url'], FILTER_SANITIZE_URL);

			$message = filter_var($this->_params['message'], FILTER_SANITIZE_STRING);

			$notif = new NotificationObject($this->_mysqli);
			$notif->uid = $uid;
			$notif->url = $url;
			$notif->message = $message;

			$notif->create();

			// TODO create stream

		} catch (Exception $e){
			return $e;
		}
	}

	public function loadNotification(){

	}

	/**
	 * createStreamNotification()
	 * Creates a new pusher stream based on users unique notification hash.
	 */
	private function createStreamNotification(){

	}
	public function fetchAllUserNotification(){

	}
}

