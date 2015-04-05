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
require_once "include/socket/Emitter.php";

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

			$message = filter_var($this->_params['message'], FILTER_SANITIZE_STRING);

			$notif = new NotificationObject($this->_mysqli);
			$notif->uid = $uid;
			$notif->url = $url;
			$notif->message = $message;

			$notif->create();

			// create stream
			$this->pushNotification($notif);

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

	/* pushNotification()
	 * Creates a new pusher stream based on users unique notification hash.
	 */
	private function pushNotification($notif){

		$user = new UserObject($this->_mysqli);
		$user->uid = $notif->uid;

		$user->load();

		$redis = new \Redis(); // Using the Redis extension provided client
		$redis->connect('127.0.0.1', '6379');

		$emitter = new SocketIO\Emitter($redis);
		$emitter->emit($user->notification_hash, array('id' => $notif->id, 'url' => $notif->url, 'message' => $notif->message));

	}

	/* fetchAllUserNotification()
	 * Fetchs all notification ids of the current user
	 */
	public function fetchAllUserNotification(){
		try {
			if(!isset($_SESSION['uid'])) {
				throw new UserException("unset vars: uid ", __FUNCTION__);
			}

			$uid = filter_var($_SESSION['uid'], FILTER_SANITIZE_NUMBER_INT);
			
			$notif = new NotificationObject($this->_mysqli);
			$notif->uid = $uid;
		
			return $notif->fetchNotifications();

		} catch (Exception $e){
			return $e;
		}
	}
}

