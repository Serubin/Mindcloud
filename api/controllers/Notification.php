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
require_once "models/NotificationObject.php";
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
			
			$uid = filter_var($this->_params['uid'], FILTER_SANITIZE_NUMBER_INT);
			
			$url = filter_var($this->_params['url'], FILTER_SANITIZE_URL);

			$message = $this->_params['message'];

			$notif = new NotificationObject($this->_mysqli);
			$notif->uid = $uid;
			$notif->url = $url;
			$notif->message = $message;

			$notif->create();

			// create stream
			$notif->pushNotification();

			return true;
		} catch (Exception $e){
			return $e;
		}
	}

	/* loadNotification()
	 * Loads all data for specified notification
	 */
	public function loadNotification(){
		try {
			if(!isset($this->_params['id'], $_SESSION['uid'])) {
				throw new UserException("Unset vars: id, uid", __FUNCTION__);
			}

			$notification = new NotificationObject($this->_mysqli);
			$notification->id = $this->_params['id'];
			$notification->uid = $_SESSION['uid'];
			$notification->load();

			return Array(
				"id" 		=> $notification->id,
				"uid" 		=> $notification->uid,
				"url" 		=> $notification->url,
				"message" 	=> $notification->message,
				"time" 		=> $notification->time
			);
		} catch(Exception $e) {
			return $e;
		}
	}

	/* loadArrayNotification()
	 * Loads all data for a list notifications
	 */
	public function loadArrayNotification(){
		try {
			if(!isset($this->_params['ids'], $this->_params['read'], $_SESSION['uid'])) {
				throw new UserException("Unset vars: ids, uid", __FUNCTION__);
			}

			$ids = json_decode($this->_params['ids']);

			$result = Array();

			foreach ($ids as $key => $value){
				$notification = new NotificationObject($this->_mysqli);
				$notification->id = $value;
				$notification->read = $this->_params['read'];
				$notification->uid = $_SESSION['uid'];
				$notification->load();

				$result[$key] = Array(
					"id" 		=> $notification->id,
					"uid" 		=> $notification->uid,
					"url" 		=> $notification->url,
					"message" 	=> $notification->message,
					"time" 		=> $notification->time
				);
			}

			return $result;
		} catch(Exception $e) {
			return $e;
		}
	}

	/**
	 * Updates seen flag
	 * @param id notification id
	 * @param seen - seen value
	 */
	public function updateNotification(){
		try {

			if(!isset($this->_params['id'], $this->_params['read'])) {
				throw new UserException("Unset vars: ids, uid", __FUNCTION__);
			}
			$notif = new NotificationObject($this->_mysqli);
			$notif->id = $this->_params['id'];
			$notif->read = $this->_params['read'];
			$notif->updateRead();

			return true;

		} catch(Exception $e){
			return $e;
		}
	}

	/* fetchAllUserNotification()
	 * Fetchs all notification ids of the current user
	 */
	public function fetchAllIdNotification(){
		try {
			if(!isset($this->_params['read'], $_SESSION['uid'])) {
				throw new UserException("unset vars: uid ", __FUNCTION__);
			}

			$uid = filter_var($_SESSION['uid'], FILTER_SANITIZE_NUMBER_INT);
			
			$notif = new NotificationObject($this->_mysqli);
			$notif->uid = $uid;
			$notif->read = $this->_params['read'];
		
			return $notif->fetchNotifications();

		} catch (Exception $e){
			return $e;
		}
	}
}

